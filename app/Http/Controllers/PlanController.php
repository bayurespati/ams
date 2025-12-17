<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\ItemType;
use App\Models\ItemVariety;
use App\Models\Plan;
use App\Models\PlanItem;
use App\Models\Brand;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    /**
     * =========================
     * GET BY ID
     * =========================
     */
    public function getById(Request $request)
    {
        $plan = Plan::with([
            'items.item_type',
            'items.item_variety',
            'items.company',
            'items.brands'
        ])->where('uuid', $request->id)->first();

        if (!$plan) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $data = [
            'uuid' => $plan->uuid,
            'project_id' => $plan->project_id,
            'project_name' => $plan->project_name,
            'judul' => $plan->judul,
            'is_lop' => (bool) $plan->is_lop,
            'file_prpo' => $plan->file_prpo,
            'no_prpo' => $plan->no_prpo,
            'items' => $plan->items->map(fn ($it) => [
                'tipe_barang_id' => optional($it->item_type)->uuid,
                'jenis_barang_id' => optional($it->item_variety)->uuid,
                'nama_barang' => $it->nama_barang,
                'jumlah_barang' => $it->jumlah_barang,
                'mitra' => [
                    'uuid' => optional($it->company)->uuid,
                    'name' => optional($it->company)->name,
                ],
                'brands' => $it->brands->map(fn ($b) => [
                    'uuid' => $b->uuid,
                    'name' => $b->name
                ])
            ]),
            'created_at' => $plan->created_at
        ];

        return response()->json(['data' => $data, 'message' => 'Success get data plan'], 200);
    }

    /**
     * =========================
     * GET ALL
     * =========================
     */
    public function getAll()
    {
        $plans = Plan::with([
            'items.item_type',
            'items.item_variety',
            'items.company',
            'items.brands'
        ])->get()->map(fn ($plan) => [
            'uuid' => $plan->uuid,
            'project_id' => $plan->project_id,
            'project_name' => $plan->project_name,
            'judul' => $plan->judul,
            'is_lop' => (bool) $plan->is_lop,
            'file_prpo' => $plan->file_prpo,
            'no_prpo' => $plan->no_prpo,
            'items' => $plan->items->map(fn ($it) => [
                'nama_barang' => $it->nama_barang,
                'jumlah_barang' => $it->jumlah_barang,
                'mitra' => [
                    'uuid' => optional($it->company)->uuid,
                    'name' => optional($it->company)->name,
                ],
                'brands' => $it->brands->map(fn ($b) => [
                    'uuid' => $b->uuid,
                    'name' => $b->name
                ])
            ]),
            'created_at' => $plan->created_at
        ]);

        return response()->json(['data' => $plans, 'message' => 'Success get data plans'], 200);
    }

    /**
     * =========================
     * STORE DATA
     * =========================
     */
    public function store(StorePlanRequest $request)
    {
        DB::beginTransaction();

        try {
            $plan = Plan::create([
                'uuid' => (string) Str::uuid(),
                'is_lop' => (bool) $request->is_lop,
                'project_id' => $request->is_lop ? $request->project_id : null,
                'project_name' => !$request->is_lop ? $request->project_name : null,
                'judul' => $request->judul,
                'no_prpo' => $request->no_prpo,
                'file_prpo' => $request->hasFile('file_prpo')
                    ? Storage::disk('public')->put('plan', $request->file_prpo)
                    : null,
            ]);

            foreach ($request->items as $it) {

                $company = Company::where('uuid', $it['company_id'])->firstOrFail();
                $tipe = ItemType::where('uuid', $it['tipe_barang_id'])->firstOrFail();
                $jenis = ItemVariety::where('uuid', $it['jenis_barang_id'])->firstOrFail();

                $planItem = PlanItem::create([
                    'plan_id' => $plan->id,
                    'company_id' => $company->id,
                    'tipe_barang_id' => $tipe->id,
                    'jenis_barang_id' => $jenis->id,
                    'nama_barang' => $it['nama_barang'],
                    'jumlah_barang' => $it['jumlah_barang'],
                ]);

                $brands = Brand::whereIn('uuid', $it['brand_ids'])->pluck('id');
                $planItem->brands()->attach($brands);
            }

            DB::commit();

            return response()->json([
                'data' => $plan->load('items.company', 'items.brands'),
                'message' => 'Success store data plan'
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed store data plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * =========================
     * UPDATE DATA
     * =========================
     */
    public function update(UpdatePlanRequest $request)
    {
        $plan = Plan::where('uuid', $request->id)->first();
        if (!$plan) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        DB::beginTransaction();

        try {
            $plan->update([
                'is_lop' => (bool) $request->is_lop,
                'project_id' => $request->is_lop ? $request->project_id : null,
                'project_name' => !$request->is_lop ? $request->project_name : null,
                'judul' => $request->judul,
                'no_prpo' => $request->no_prpo,
            ]);

            if ($request->hasFile('file_prpo')) {
                $plan->file_prpo = Storage::disk('public')->put('plan', $request->file_prpo);
                $plan->save();
            }

            // reset items
            $plan->items()->delete();

            foreach ($request->items as $it) {

                $company = Company::where('uuid', $it['company_id'])->firstOrFail();
                $tipe = ItemType::where('uuid', $it['tipe_barang_id'])->firstOrFail();
                $jenis = ItemVariety::where('uuid', $it['jenis_barang_id'])->firstOrFail();

                $planItem = PlanItem::create([
                    'plan_id' => $plan->id,
                    'company_id' => $company->id,
                    'tipe_barang_id' => $tipe->id,
                    'jenis_barang_id' => $jenis->id,
                    'nama_barang' => $it['nama_barang'],
                    'jumlah_barang' => $it['jumlah_barang'],
                ]);

                $brands = Brand::whereIn('uuid', $it['brand_ids'])->pluck('id');
                $planItem->brands()->attach($brands);
            }

            DB::commit();

            return response()->json([
                'data' => $plan->load('items.company', 'items.brands'),
                'message' => 'Success update data plan'
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed update data plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * =========================
     * DELETE
     * =========================
     */
    public function destroy(Request $request)
    {
        $plan = Plan::where('uuid', $request->id)->first();
        if (!$plan) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $plan->delete();
        return response()->json(['message' => 'Success delete data plan'], 200);
    }

    /**
     * =========================
     * GET GARBAGE
     * =========================
     */
    public function getGarbage()
    {
        $plans = Plan::onlyTrashed()
            ->with('items.company', 'items.brands')
            ->get();

        return response()->json([
            'data' => $plans,
            'message' => 'Success get garbage plans'
        ], 200);
    }

    /**
     * =========================
     * RESTORE
     * =========================
     */
    public function restore(Request $request)
    {
        $plan = Plan::withTrashed()->where('uuid', $request->id)->first();
        if (!$plan) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $plan->restore();
        return response()->json(['message' => 'Success restore data plan'], 200);
    }
}
