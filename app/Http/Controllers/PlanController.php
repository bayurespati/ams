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

class PlanController extends Controller
{
    // Get By id
    public function getById(Request $request)
    {
        $plan = Plan::with(['items.item_type', 'items.item_variety', 'items.company', 'items.brands', 'companies'])
            ->where('uuid', $request->id)->first();

        if (!$plan)
            return response()->json(['data' => $plan, 'message' => 'Data not found'], 404);

        $data = [
            "uuid" => $plan->uuid,
            "project_id" => $plan->project_id,
            "project_name" => $plan->project_name,
            "judul" => $plan->judul,
            "is_lop" => (bool)$plan->is_lop,
            "file_prpo" => $plan->file_prpo,
            "no_prpo" => $plan->no_prpo,
            'items' => $plan->items->map(function ($it) {
                return [
                    'tipe_barang_id' => optional($it->item_type)->uuid,
                    'jenis_barang_id' => optional($it->item_variety)->uuid,
                    'nama_barang' => $it->nama_barang,
                    'jumlah_barang' => $it->jumlah_barang,
                    'company_id' => optional($it->company)->uuid,
                    'brands' => $it->brands->map(fn($b) => ['uuid' => $b->uuid, 'name' => $b->name])
                ];
            }),
            'mitra' => $plan->companies->map(fn($c) => ['uuid' => $c->uuid, 'name' => $c->name]),
            'created_at' => $plan->created_at,
        ];

        return response()->json(['data' => $data, 'message' => 'Success get data plan'], 200);
    }

    // Get All
    public function getAll()
    {
        $plans = Plan::with(['items.item_type', 'items.item_variety', 'items.company', 'items.brands', 'companies'])
            ->get()
            ->map(function ($plan) {
                return [
                    "uuid" => $plan->uuid,
                    "project_id" => $plan->project_id,
                    "project_name" => $plan->project_name,
                    "judul" => $plan->judul,
                    "is_lop" => (bool)$plan->is_lop,
                    "file_prpo" => $plan->file_prpo,
                    "no_prpo" => $plan->no_prpo,
                    'items' => $plan->items->map(fn($it) => [
                        'tipe_barang_id' => optional($it->item_type)->uuid,
                        'jenis_barang_id' => optional($it->item_variety)->uuid,
                        'nama_barang' => $it->nama_barang,
                        'jumlah_barang' => $it->jumlah_barang,
                        'company_id' => optional($it->company)->uuid,
                        'brands' => $it->brands->map(fn($b) => ['uuid' => $b->uuid, 'name' => $b->name])
                    ]),
                    'mitra' => $plan->companies->map(fn($c) => ['uuid' => $c->uuid, 'name' => $c->name]),
                    'created_at' => $plan->created_at,
                ];
            });

        return response()->json(['data' => $plans, 'message' => 'Success get data plans'], 200);
    }

    // Store data
    public function store(StorePlanRequest $request)
    {
        $companies = Company::whereIn('uuid', $request->company_ids)->get();
        if ($companies->count() !== count($request->company_ids)) {
            return response()->json(['message' => 'Salah satu atau lebih company tidak ditemukan'], 404);
        }

        $plan = new Plan();
        $plan->uuid = (string) Str::uuid();
        $plan->is_lop = (bool)$request->is_lop;

        if ($plan->is_lop) {
            $plan->project_id = $request->project_id;
            $plan->project_name = null;
        } else {
            $plan->project_id = null;
            $plan->project_name = $request->project_name;
        }

        $plan->judul = $request->judul;
        $plan->no_prpo = $request->no_prpo;

        if ($request->hasFile('file_prpo')) {
            $plan->file_prpo = Storage::disk('public')->put('plan', $request->file_prpo);
        }

        $plan->save();
        $plan->companies()->attach($companies->pluck('id')->toArray());

        foreach ($request->items as $it) {
            $tipe = ItemType::where('uuid', $it['tipe_barang_id'])->first();
            $jenis = ItemVariety::where('uuid', $it['jenis_barang_id'])->first();
            $company = Company::where('uuid', $it['company_id'])->first();

            if (!$tipe || !$jenis || !$company) {
                $plan->delete();
                return response()->json(['message' => 'Tipe, jenis, atau company tidak ditemukan'], 404);
            }

            $planItem = PlanItem::create([
                'plan_id' => $plan->id,
                'company_id' => $company->id,
                'tipe_barang_id' => $tipe->id,
                'jenis_barang_id' => $jenis->id,
                'nama_barang' => $it['nama_barang'],
                'jumlah_barang' => $it['jumlah_barang'],
            ]);

            if (isset($it['brand_ids']) && is_array($it['brand_ids'])) {
                $brands = Brand::whereIn('uuid', $it['brand_ids'])->pluck('id')->toArray();
                $planItem->brands()->sync($brands);
            }
        }

        return response()->json([
            'data' => $plan->load(['companies', 'items.item_type', 'items.item_variety', 'items.company', 'items.brands']),
            'message' => 'Success store data plan'
        ], 200);
    }

    // Update data
    public function update(Request $request)
    {
        $plan = Plan::where('uuid', $request->id)->first();
        if (!$plan) return response()->json(['data' => $plan, 'message' => 'Data not found'], 404);

        $request->validate((new UpdatePlanRequest())->rules($plan));

        $companies = Company::whereIn('uuid', $request->company_ids ?? [])->get();
        if ($companies->count() !== count($request->company_ids ?? [])) {
            return response()->json(['message' => 'Salah satu atau lebih company tidak ditemukan'], 404);
        }

        $plan->is_lop = (bool)($request->is_lop ?? $plan->is_lop);
        if ($plan->is_lop) {
            $plan->project_id = $request->project_id;
            $plan->project_name = null;
        } else {
            $plan->project_id = null;
            $plan->project_name = $request->project_name;
        }

        $plan->judul = $request->judul;
        $plan->no_prpo = $request->no_prpo ?? $plan->no_prpo;

        if ($request->hasFile('file_prpo')) {
            $plan->file_prpo = Storage::disk('public')->put('plan', $request->file_prpo);
        }
        $plan->save();

        $plan->items()->delete();

        foreach ($request->items as $it) {
            $tipe = ItemType::where('uuid', $it['tipe_barang_id'])->first();
            $jenis = ItemVariety::where('uuid', $it['jenis_barang_id'])->first();
            $company = Company::where('uuid', $it['company_id'])->first();

            if (!$tipe || !$jenis || !$company) {
                return response()->json(['message' => 'Tipe, jenis, atau company tidak ditemukan'], 404);
            }

            $planItem = PlanItem::create([
                'plan_id' => $plan->id,
                'company_id' => $company->id,
                'tipe_barang_id' => $tipe->id,
                'jenis_barang_id' => $jenis->id,
                'nama_barang' => $it['nama_barang'],
                'jumlah_barang' => $it['jumlah_barang'],
            ]);

            if (isset($it['brand_ids']) && is_array($it['brand_ids'])) {
                $brands = Brand::whereIn('uuid', $it['brand_ids'])->pluck('id')->toArray();
                $planItem->brands()->sync($brands);
            }
        }

        $plan->companies()->sync($companies->pluck('id')->toArray());

        return response()->json(['data' => $plan->load(['companies', 'items.item_type', 'items.item_variety', 'items.company', 'items.brands']), 'message' => 'Success update data plan'], 200);
    }

    // Delete data
    public function destroy(Request $request)
    {
        $model = Plan::where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data plan'], 200);
    }

    // Softdelete Get Garbage
    public function getGarbage()
    {
        $plans = Plan::onlyTrashed()->with(['items.item_type', 'items.item_variety', 'items.company', 'items.brands', 'companies'])->get()->map(function ($plan) {
            return [
                "uuid" => $plan->uuid,
                "project_id" => $plan->project_id,
                "project_name" => $plan->project_name,
                "judul" => $plan->judul,
                "is_lop" => (bool)$plan->is_lop,
                "file_prpo" => $plan->file_prpo,
                "no_prpo" => $plan->no_prpo,
                'items' => $plan->items->map(fn($it) => [
                    'tipe_barang_id' => optional($it->item_type)->uuid,
                    'jenis_barang_id' => optional($it->item_variety)->uuid,
                    'nama_barang' => $it->nama_barang,
                    'jumlah_barang' => $it->jumlah_barang,
                    'company_id' => optional($it->company)->uuid,
                    'brands' => $it->brands->map(fn($b) => ['uuid' => $b->uuid, 'name' => $b->name])
                ]),
                'mitra' => $plan->companies->map(fn($c) => ['uuid' => $c->uuid, 'name' => $c->name]),
                'created_at' => $plan->created_at,
            ];
        });

        return response()->json(['data' => $plans, 'message' => 'Success get garbage plans'], 200);
    }

    // Restore data softdelete
    public function restore(Request $request)
    {
        $model = Plan::withTrashed()->where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data plan'], 200);
    }
}
