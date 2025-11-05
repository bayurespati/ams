<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\ItemType;
use App\Models\ItemVariety;
use App\Models\Plan;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema; 
use Illuminate\Support\Facades\DB;  

class PlanController extends Controller
{
    // Get By id
    public function getById(Request $request)
    {
        $plan = Plan::with(['item_type', 'item_variety', 'companies'])->where('uuid', $request->id)->first();
        if (!$plan)
            return response()->json(['data' => $plan, 'message' => 'Data not found'], 404);

        $data = [
            "uuid" => $plan->uuid,
            "project_id" => $plan->project_id,
            "project_name" => $plan->project_name,
            "judul" => $plan->judul,
            "nama_barang" => $plan->nama_barang,
            "jumlah_barang" => $plan->jumlah_barang,
            "is_lop" => (bool)$plan->is_lop,
            "file_prpo" => $plan->file_prpo,
            "no_prpo" => $plan->no_prpo,
            'jenis_barang_id' => optional($plan->item_variety)->uuid,
            'tipe_barang_id' => optional($plan->item_type)->uuid,
            'mitra' => $plan->companies->map(function ($c) {
                return ['uuid' => $c->uuid, 'name' => $c->name];
            }),
            'created_at' => $plan->created_at,
        ];

        return response()->json(['data' => $data, 'message' => 'Success get data plan'], 200);
    }

    // Get All
    public function getAll()
    {
        $plans = Plan::with(['item_type', 'item_variety', 'companies'])->get()->map(function ($plan) {
            return [
                "uuid" => $plan->uuid,
                "project_id" => $plan->project_id,
                "project_name" => $plan->project_name,
                "judul" => $plan->judul,
                "nama_barang" => $plan->nama_barang,
                "jumlah_barang" => $plan->jumlah_barang,
                "is_lop" => (bool)$plan->is_lop,
                "file_prpo" => $plan->file_prpo,
                "no_prpo" => $plan->no_prpo,
                'jenis_barang_id' => optional($plan->item_variety)->uuid,
                'tipe_barang_id' => optional($plan->item_type)->uuid,
                'mitra' => $plan->companies->map(fn($c)=> ['uuid'=>$c->uuid, 'name'=>$c->name]),
                'created_at' => $plan->created_at,
            ];
        });

        return response()->json(['data' => $plans, 'message' => 'Success get data plans'], 200);
    }

    // Store data
    public function store(StorePlanRequest $request)
    {
        $tipe_barang = ItemType::where('uuid', $request->tipe_barang_id)->first();
        $jenis_barang = ItemVariety::where('uuid', $request->jenis_barang_id)->first();
        if (!$tipe_barang)
            return response()->json(['message' => 'Data tipe barang not found'], 404);
        if (!$jenis_barang)
            return response()->json(['message' => 'Data jenis barang not found'], 404);

        // resolve company uuids -> ids
        $companies = Company::whereIn('uuid', $request->company_ids)->get();
        if ($companies->count() !== count($request->company_ids)) {
            return response()->json(['message' => 'Salah satu atau lebih company tidak ditemukan'], 404);
        }

        $plan = new Plan();
        $plan->uuid = (string) Str::uuid();
        $plan->is_lop = (bool) $request->is_lop;

        // Handle project based on LOP/Non-LOP
        if ($plan->is_lop) {
            $plan->project_id = $request->project_id; // Ambil project_id yang sudah ada
            $plan->project_name = null; // Tidak perlu project_name
        } else {
            $plan->project_id = null; // Tidak ada project_id
            $plan->project_name = $request->project_name; // Ambil project_name dari input
        }

        $plan->judul = $request->judul;
        $plan->nama_barang = $request->nama_barang;
        $plan->jenis_barang_id = $jenis_barang->id;
        $plan->tipe_barang_id = $tipe_barang->id;
        $plan->jumlah_barang = $request->jumlah_barang;
        $plan->no_prpo = $request->no_prpo;

        if ($request->hasFile('file_prpo')) {
            $plan->file_prpo = Storage::disk('public')->put('plan', $request->file_prpo);
        }

        $plan->save();

        // attach mitra
        $plan->companies()->attach($companies->pluck('id')->toArray());

        return response()->json([
            'data' => $plan->load(['companies', 'item_type', 'item_variety']),
            'message' => 'Success store data plan'
        ], 200);
    }

    // Update data
    public function update(Request $request)
    {
        $tipe_barang = ItemType::where('uuid', $request->tipe_barang_id)->first();
        $jenis_barang = ItemVariety::where('uuid', $request->jenis_barang_id)->first();
        $plan = Plan::where('uuid', $request->id)->first();

        if (!$tipe_barang)
            return response()->json(['message' => 'Data tipe barang not found'], 404);
        if (!$jenis_barang)
            return response()->json(['message' => 'Data jenis barang not found'], 404);
        if (!$plan)
            return response()->json(['data' => $plan, 'message' => 'Data not found'], 404);

        $request->validate((new UpdatePlanRequest())->rules($plan));

        // resolve company uuids -> ids
        $companies = Company::whereIn('uuid', $request->company_ids ?? [])->get();
        if ($companies->count() !== count($request->company_ids ?? [])) {
            return response()->json(['message' => 'Salah satu atau lebih company tidak ditemukan'], 404);
        }

        // Tangani project sesuai is_lop
        $plan->is_lop = (bool) ($request->is_lop ?? $plan->is_lop);
        $plan->is_lop = (bool) ($request->is_lop ?? $plan->is_lop);
        if ($plan->is_lop) {
            $plan->project_id = $request->project_id; // Ambil project_id yang sudah ada
            $plan->project_name = null; // Tidak perlu project_name
        } else {
            $plan->project_id = null; // Tidak ada project_id
            $plan->project_name = $request->project_name; // Ambil project_name dari input
        }

        $plan->judul = $request->judul;
        $plan->nama_barang = $request->nama_barang;
        $plan->jenis_barang_id = $jenis_barang->id;
        $plan->tipe_barang_id = $tipe_barang->id;
        $plan->jumlah_barang = $request->jumlah_barang;
        $plan->no_prpo = $request->no_prpo ?? $plan->no_prpo;
        if ($request->hasFile('file_prpo'))
            $plan->file_prpo = Storage::disk('public')->put('plan', $request->file_prpo);
        $plan->save();

        // sync mitra
        $plan->companies()->sync($companies->pluck('id')->toArray());

        return response()->json(['data' => $plan->load('companies'), 'message' => 'Success update data plan'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = Plan::where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data plan'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = Plan::withTrashed()->where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data plan'], 200);
    }
}
