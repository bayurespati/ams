<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoInRequest;
use App\Http\Requests\UpdateDoInRequest;
use App\Imports\AddItemDoInImport;
use App\Models\DoIn;
use App\Models\PO;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DoInController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $do_in = DoIn::find($request->id);
        if (!$do_in)
            return response()->json(['data' => $do_in, 'message' => 'Data not found'], 404);
        return response()->json(['data' => $do_in, 'message' => 'Success get data do in'], 200);
    }

    //Get All
    public function getAll()
    {
        $do_in = DoIn::all();

        return response()->json(['data' => $do_in, 'message' => 'Success get data do in'], 200);
    }

    //Store data
    public function store(StoreDoInRequest $request)
    {
        $po = PO::find($request->po_id);
        if (!$po)
            return response()->json(['message' => 'Data po not found'], 404);
        $do_in = new DoIn();
        $do_in->po_id = $request->po_id;
        $do_in->no_do = $request->no_do;
        $do_in->lokasi_gudang = $request->lokasi_gudang;
        $do_in->owner_id = $request->owner_id;
        $do_in->owner_type = $request->owner_type;
        $do_in->keterangan = $request->keterangan;
        $do_in->tanggal_masuk = $request->tanggal_masuk;
        $do_in->no_gr = $request->no_gr;
        $do_in->file_evidence = Storage::disk('public')->put('do_in', $request->file_evidence);
        $do_in->save();

        return response()->json(['data' => $do_in, 'message' => 'Success store data do in'], 200);
    }


    //Update data
    public function update(Request $request)
    {
        $po = PO::find($request->po_id);
        if (!$po)
            return response()->json(['message' => 'Data po not found'], 404);
        $do_in = DoIn::find($request->id);
        if (!$do_in)
            return response()->json(['data' => $do_in, 'message' => 'Data not found'], 404);
        $request->validate((new UpdateDoInRequest())->rules($do_in));
        $do_in->po_id = $request->po_id;
        $do_in->no_do = $request->no_do;
        $do_in->lokasi_gudang = $request->lokasi_gudang;
        $do_in->owner_id = $request->owner_id;
        $do_in->owner_type = $request->owner_type;
        $do_in->keterangan = $request->keterangan;
        $do_in->tanggal_masuk = $request->tanggal_masuk;
        $do_in->no_gr = $request->no_gr;
        if ($request->file_evidence)
            $do_in->file_evidence = Storage::disk('public')->put('do_in', $request->file_evidence);
        $do_in->save();

        return response()->json(['data' => $do_in, 'message' => 'Success update data do in'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = DoIn::find($request->id);
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data do in'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = DoIn::withTrashed()->find($request->id);
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model = DoIn::withTrashed()->find($request->id)->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data do in'], 200);
    }
}
