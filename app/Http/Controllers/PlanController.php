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
    // Get By ID
    public function getById(Request $request)
    {
        $plan = Plan::with([
            'items.item_type',
            'items.item_variety',
            'companies',
            'brands'
        ])->where('uuid', $request->id)->first();

        if (!$plan) {
            return response()->json(['data' => null, 'message' => 'Data not found'], 404);
        }

        $data = [
            "uuid" => $plan->uuid,
            "project_id" => $plan->project_id,
            "project_name" => $plan->project_name,
            "judul" => $plan->judul,
            "is_lop" => (bool) $plan->is_lop,
            "file_prpo" => $plan->file_prpo,
            "no_prpo" => $plan->no_prpo,
            "items" => $plan->items->map(fn ($it) => [
                'tipe_barang_id' => optional($it->item_type)->uuid,
                'jenis_barang_id' => optional($it->item_variety)->uuid,
                'nama_barang' => $it->nama_barang,
                'jumlah_barang' => $it->jumlah_barang,
            ]),
            "mitra" => $plan->companies->map(fn ($c) => [
                'uuid' => $c->uuid,
                'name' => $c->name
            ]),
            "brands" => $plan->brands->map(fn ($b) => [
                'uuid' => $b->uuid,
                'name' => $b->name,
                'alias' => $b->alias,
            ]),
            "created_at" => $plan->created_at,
        ];

        return response()->json(['data' => $data, 'message' => 'Success get data plan'], 200);
    }

    // Get All
    public function getAll()
    {
        $plans = Plan::with([
            'items.item_type',
            'items.item_variety',
            'companies',
            'brands'
        ])->get()->map(function ($plan) {
            return [
                "uuid" => $plan->uuid,
                "project_id" => $plan->project_id,
                "project_name" => $plan->project_name,
                "judul" => $plan->judul,
                "is_lop" => (bool) $plan->is_lop,
                "file_prpo" => $plan->file_prpo,
                "no_prpo" => $plan->no_prpo,
                "items" => $plan->items->map(fn ($it) => [
                    'tipe_barang_id' => optional($it->item_type)->uuid,
                    'jenis_barang_id' => optional($it->item_variety)->uuid,
                    'nama_barang' => $it->nama_barang,
                    'jumlah_barang' => $it->jumlah_barang,
                ]),
                "mitra" => $plan->companies->map(fn ($c) => [
                    'uuid' => $c->uuid,
                    'name' => $c->name
                ]),
                "brands" => $plan->brands->map(fn ($b) => [
                    'uuid' => $b->uuid,
                    'name' => $b->name,
                    'alias' => $b->alias,
                ]),
                "created_at" => $plan->created_at,
            ];
        });

        return response()->json(['data' => $plans, 'message' => 'Success get data plans'], 200);
    }

    // Store
    public function store(StorePlanRequest $request)
    {
        $companies = Company::whereIn('uuid', $request->company_ids)->get();
        if ($companies->count() !== count($request->company_ids)) {
            return response()->json(['message' => 'Salah satu atau lebih company tidak ditemukan'], 404);
        }

        $brands = Brand::whereIn('uuid', $request->brand_ids)->get();
        if ($brands->count() !== count($request->brand_ids)) {
            return response()->json(['message' => 'Salah satu atau lebih brand tidak ditemukan'], 404);
        }

        $plan = new Plan();
        $plan->uuid = (string) Str::uuid();
        $plan->is_lop = (bool) $request->is_lop;

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

        foreach ($request->items as $it) {
            $tipe = ItemType::where('uuid', $it['tipe_barang_id'])->first();
            $jenis = ItemVariety::where('uuid', $it['jenis_barang_id'])->first();

            if (!$tipe || !$jenis) {
                $plan->delete();
                return response()->json(['message' => 'Tipe atau jenis barang tidak ditemukan'], 404);
            }

            PlanItem::create([
                'plan_id' => $plan->id,
                'tipe_barang_id' => $tipe->id,
                'jenis_barang_id' => $jenis->id,
                'nama_barang' => $it['nama_barang'],
                'jumlah_barang' => $it['jumlah_barang'],
            ]);
        }

        $plan->companies()->attach($companies->pluck('id')->toArray());
        $plan->brands()->attach($brands->pluck('id')->toArray());

        return response()->json([
            'data' => $plan->load(['companies', 'brands', 'items.item_type', 'items.item_variety']),
            'message' => 'Success store data plan'
        ], 200);
    }

    // Update
    public function update(Request $request)
    {
        $plan = Plan::where('uuid', $request->id)->first();
        if (!$plan) {
            return response()->json(['data' => null, 'message' => 'Data not found'], 404);
        }

        $request->validate((new UpdatePlanRequest())->rules($plan));

        $companies = Company::whereIn('uuid', $request->company_ids)->get();
        if ($companies->count() !== count($request->company_ids)) {
            return response()->json(['message' => 'Salah satu atau lebih company tidak ditemukan'], 404);
        }

        $brands = Brand::whereIn('uuid', $request->brand_ids)->get();
        if ($brands->count() !== count($request->brand_ids)) {
            return response()->json(['message' => 'Salah satu atau lebih brand tidak ditemukan'], 404);
        }

        $plan->is_lop = (bool) $request->is_lop;
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

        $plan->items()->delete();
        foreach ($request->items as $it) {
            $tipe = ItemType::where('uuid', $it['tipe_barang_id'])->first();
            $jenis = ItemVariety::where('uuid', $it['jenis_barang_id'])->first();

            if (!$tipe || !$jenis) {
                return response()->json(['message' => 'Tipe atau jenis barang tidak ditemukan'], 404);
            }

            PlanItem::create([
                'plan_id' => $plan->id,
                'tipe_barang_id' => $tipe->id,
                'jenis_barang_id' => $jenis->id,
                'nama_barang' => $it['nama_barang'],
                'jumlah_barang' => $it['jumlah_barang'],
            ]);
        }

        $plan->companies()->sync($companies->pluck('id')->toArray());
        $plan->brands()->sync($brands->pluck('id')->toArray());

        return response()->json([
            'data' => $plan->load(['companies', 'brands', 'items.item_type', 'items.item_variety']),
            'message' => 'Success update data plan'
        ], 200);
    }
}
