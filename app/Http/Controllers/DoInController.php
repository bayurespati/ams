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
use Illuminate\Support\Str;

class DoInController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $do_in = DoIn::where('id', $request->id)->with('po')->first();
        if (!$do_in)
            return response()->json(['data' => $do_in, 'message' => 'Data not found'], 404);
        return response()->json(['data' => $do_in, 'message' => 'Success get data do in'], 200);
    }

    //Get All
    public function getAll()
    {
        $do_in = DoIn::with('po')->get();
        $do_in = DoIn::with(['po'])->get()->map(function ($do) {
            return [
                "no_do" => $do->no_do,
                "lokasi_gudang" => $do->lokasi_gudang,
                "owner_id" => $do->owner_id,
                "owner_type" => $do->owner_type,
                "keterangan" => $do->keterangan,
                "tanggal_masuk" => $do->tanggal_masuk,
                "no_gr" => $do->no_gr,
                "file_evidence" => $do->file_evidence,
                'po_id' => optional($do->po)->uuid,
            ];
        });

        return response()->json(['data' => $do_in, 'message' => 'Success get data do in'], 200);
    }

    //Get Garbage
    public function getGarbage()
    {
        $do_in = DoIn::onlyTrashed()->get();

        return response()->json(['data' => $do_in, 'message' => 'Success get data garbage  do in'], 200);
    }

    //Store data
    public function store(StoreDoInRequest $request)
    {
        $po = PO::where('uuid', "=", $request->po_id)->first();
        // $owner = Owner::where('uuid', "=", $request->owner_id)->first();
        if (!$po)
            return response()->json(['message' => 'Data po not found'], 404);
        // if (!$owner)
        //     return response()->json(['message' => 'Data owner not found'], 404);
        $do_in = new DoIn();
        $do_in->uuid = Str::uuid();
        $do_in->po_id = $po->id;
        $do_in->no_do = $request->no_do;
        $do_in->lokasi_gudang = $request->lokasi_gudang;
        $do_in->owner_id = $request->owner_id;
        $do_in->keterangan = $request->keterangan;
        $do_in->tanggal_masuk = $request->tanggal_masuk;
        $do_in->no_gr = $request->no_gr;
        $do_in->file_evidence = Storage::disk('public')->put('do_in', $request->file_evidence);
        $do_in->save();

        return response()->json(['data' => $do_in->load('po'), 'message' => 'Success store data do in'], 200);
    }


    //Update data
    public function update(Request $request)
    {
        $po = PO::where('uuid', "=", $request->po_id)->first();
        // $owner = Owner::where('uuid', "=", $request->owner_id)->first();
        $do_in = DoIn::where('uuid', "=", $request->id)->first();
        if (!$po)
            return response()->json(['message' => 'Data po not found'], 404);
        // if (!$owner)
        //     return response()->json(['message' => 'Data owner not found'], 404);
        if (!$do_in)
            return response()->json(['data' => $do_in, 'message' => 'Data not found'], 404);
        $request->validate((new UpdateDoInRequest())->rules($do_in));
        $do_in->po_id = $po->id;
        $do_in->no_do = $request->no_do;
        $do_in->lokasi_gudang = $request->lokasi_gudang;
        $do_in->owner_id = $request->owner_id;
        $do_in->keterangan = $request->keterangan;
        $do_in->tanggal_masuk = $request->tanggal_masuk;
        $do_in->no_gr = $request->no_gr;
        if ($request->file_evidence)
            $do_in->file_evidence = Storage::disk('public')->put('do_in', $request->file_evidence);
        $do_in->save();

        return response()->json(['data' => $do_in, 'message' => 'Success update data do in'], 200);
    }

    public function approve(Request $request)
    {
        $do_in = DoIn::where('uuid', $request->id)->first();
        if (!$do_in)
            return response()->json(['data' => $do_in, 'message' => 'Data not found'], 404);
        $item_do_in = ItemDoIn::where('do_id_in', $do_in->id)->where('is_approve', 0)->first();
        if ($item_do_in)
            return response()->json(['data' => $do_in, 'message' => 'Item verification not complate'], 404);

        $do_in->is_approve = true;
        $do_in->save();
        return response()->json(['data' => $do_in, 'message' => 'Success approve data do in'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = DoIn::where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data do in'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = DonIn::withTrashed()->where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data do in'], 200);
    }
}
