<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $plan = Plan::where('uuid', $request->id)->first();
        if (!$plan)
            return response()->json(['data' => $plan, 'message' => 'Data not found'], 404);

        return response()->json(['data' => $plan, 'message' => 'Success get data plan'], 200);
    }

    //Get All
    public function getAll()
    {
        $plan = Plan::all();
        return response()->json(['data' => $plan, 'message' => 'Success get data plans'], 200);
    }

    //Get Garbage
    public function getGarbage()
    {
        $plan = Plan::onlyTrashed()->get();

        return response()->json(['data' => $plan, 'message' => 'Success get data garbage plan'], 200);
    }

    //Store data
    public function store(StorePlanRequest $request)
    {
        $plan = new Plan();
        $plan->uuid = Str::uuid();
        $plan->project_id = $request->project_id;
        $plan->judul = $request->judul;
        $plan->nama_barang = $request->nama_barang;
        $plan->jenis_barang_id = $request->jenis_barang_id;
        $plan->tipe_barang_id = $request->tipe_barang_id;
        $plan->jumlah_barang = $request->jumlah_barang;
        $plan->no_prpo = $request->no_prpo;
        $plan->is_lop = $request->is_lop;
        $plan->file_prpo = Storage::disk('public')->put('plan', $request->file_prpo);
        $plan->save();

        return response()->json(['data' => $plan, 'message' => 'Success store data plan'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $plan = Plan::where('uuid', $request->id)->first();
        if (!$plan)
            return response()->json(['data' => $plan, 'message' => 'Data not found'], 404);
        $request->validate((new UpdatePlanRequest())->rules($plan));
        $plan->project_id = $request->project_id;
        $plan->judul = $request->judul;
        $plan->nama_barang = $request->nama_barang;
        $plan->jenis_barang_id = $request->jenis_barang_id;
        $plan->tipe_barang_id = $request->tipe_barang_id;
        $plan->jumlah_barang = $request->jumlah_barang;
        $plan->no_prpo = $request->no_prpo;
        if ($request->file_prpo)
            $plan->file_prpo = Storage::disk('public')->put('plan', $request->file_prpo);
        $plan->is_lop = $request->is_lop;
        $plan->save();

        return response()->json(['data' => $plan, 'message' => 'Success update data plan'], 200);
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
