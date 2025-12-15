<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoOutRequest;
use App\Http\Requests\UpdateDoOutRequest;
use App\Http\Resources\DoOutResource;
use App\Models\DoOut;
use App\Models\Plan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DoOutController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $do_out = DoOut::where('uuid', $request->id)->with('plan')->first();
        $data = new DoOutResource($do_out);
        if (!$do_out)
            return response()->json(['data' => $do_out, 'message' => 'Data not found'], 404);
        return response()->json(['data' => $data, 'message' => 'Success get data do out'], 200);
    }

    //Get All
    public function getAll()
    {
        $do_out = DoOut::with(['plan'])->get();
        $data = DoOutResource::collection($do_out);
        return response()->json(['data' => $data, 'message' => 'Success get data do out'], 200);
    }

    //Get Garbage
    public function getGarbage()
    {
        $do_out = DoOut::onlyTrashed()->get();
        return response()->json(['data' => $do_out, 'message' => 'Success get data garbage do out'], 200);
    }

    //Store data
    public function store(StoreDoOutRequest $request)
    {
        $plan = Plan::where('uuid', "=", $request->plan_id)->first();
        if (!$plan)
            return response()->json(['message' => 'Data plan not found'], 404);
        $do_out = new DoOut();
        $do_out->uuid = Str::uuid();
        $do_out->plan_id = $plan->id;
        $do_out->no_do = $request->no_do;
        $do_out->tanggal_do = $request->tanggal_do;
        $do_out->pengirim = $request->pengirim;
        $do_out->alamat_pengirim = $request->alamat_pengirim;
        $do_out->pic_pengirim = $request->pic_pengirim;
        $do_out->telpon_pengirim = $request->telpon_pengirim;
        $do_out->penerima = $request->penerima;
        $do_out->alamat_penerima = $request->alamat_penerima;
        $do_out->pic_penerima = $request->pic_penerima;
        $do_out->telpon_penerima = $request->telpon_penerima;
        $do_out->file_evidence = Storage::disk('public')->put('do_out', $request->file_evidence);
        $do_out->status = 'delivery';
        $do_out->save();

        return response()->json(['data' => $do_out->load('plan'), 'message' => 'Success store data do out'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $plan = Plan::where('uuid', "=", $request->plan_id)->first();
        $do_out = DoOut::where('uuid', "=", $request->id)->first();
        if (!$plan)
            return response()->json(['message' => 'Data plan not found'], 404);
        if (!$do_out)
            return response()->json(['data' => $do_out, 'message' => 'Data not found'], 404);
        $request->validate((new UpdateDoOutRequest())->rules($do_out));
        $do_out->plan_id = $plan->id;
        $do_out->no_do = $request->no_do;
        $do_out->tanggal_do = $request->tanggal_do;
        $do_out->pengirim = $request->pengirim;
        $do_out->alamat_pengirim = $request->alamat_pengirim;
        $do_out->pic_pengirim = $request->pic_pengirim;
        $do_out->telpon_pengirim = $request->telpon_pengirim;
        $do_out->penerima = $request->penerima;
        $do_out->alamat_penerima = $request->alamat_penerima;
        $do_out->pic_penerima = $request->pic_penerima;
        $do_out->telpon_penerima = $request->telpon_penerima;
        if ($request->file_evidence)
            $do_out->file_evidence = Storage::disk('public')->put('do_out', $request->file_evidence);
        $do_out->save();
        $do_out->load('plan');
        $data = new DoOutResource($do_out);

        return response()->json(['data' => $data, 'message' => 'Success update data do out'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = DoOut::where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data do out'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = DoOut::withTrashed()->where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data do out'], 200);
    }
}
