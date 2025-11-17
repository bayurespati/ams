<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoInRequest;
use App\Http\Requests\UpdateDoInRequest;
use App\Http\Resources\DoInResource;
use App\Imports\AddItemDoInImport;
use App\Models\Asset;
use App\Models\DoIn;
use App\Models\PO;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class DoInController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $do_in = DoIn::where('uuid', $request->id)->with('po')->first();
        $data = new DoInResource($do_in);
        if (!$do_in)
            return response()->json(['data' => $do_in, 'message' => 'Data not found'], 404);
        return response()->json(['data' => $data, 'message' => 'Success get data do in'], 200);
    }

    //Get All
    public function getAll()
    {
        $do_in = DoIn::with(['po'])->get();
        $data = DoInResource::collection($do_in);
        return response()->json(['data' => $data, 'message' => 'Success get data do in'], 200);
    }

    //Get Garbage
    public function getGarbage()
    {
        $do_in = DoIn::onlyTrashed()->get();
        return response()->json(['data' => $do_in, 'message' => 'Success get data garbage do in'], 200);
    }

    //Store data
    public function store(StoreDoInRequest $request)
    {
        $po = PO::where('uuid', "=", $request->po_id)->first();
        if (!$po)
            return response()->json(['message' => 'Data po not found'], 404);
        $do_in = new DoIn();
        $do_in->uuid = Str::uuid();
        $do_in->po_id = $po->id;
        $do_in->no_do = $request->no_do;
        $do_in->lokasi_gudang = $request->lokasi_gudang;
        $do_in->keterangan = $request->keterangan;
        $do_in->tanggal_masuk = $request->tanggal_masuk;
        $do_in->no_gr = $request->no_gr;
        $do_in->penerima = $request->penerima;
        $do_in->status = 'progress';
        $do_in->file_evidence = Storage::disk('public')->put('do_in', $request->file_evidence);
        $do_in->file_foto_terima = Storage::disk('public')->put('do_in', $request->file_foto_terima);
        $do_in->save();

        return response()->json(['data' => $do_in->load('po'), 'message' => 'Success store data do in'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $po = PO::where('uuid', "=", $request->po_id)->first();
        $do_in = DoIn::where('uuid', "=", $request->id)->first();
        if (!$po)
            return response()->json(['message' => 'Data po not found'], 404);
        if (!$do_in)
            return response()->json(['data' => $do_in, 'message' => 'Data not found'], 404);
        $request->validate((new UpdateDoInRequest())->rules($do_in));
        $do_in->po_id = $po->id;
        $do_in->no_do = $request->no_do;
        $do_in->lokasi_gudang = $request->lokasi_gudang;
        $do_in->keterangan = $request->keterangan;
        $do_in->tanggal_masuk = $request->tanggal_masuk;
        $do_in->no_gr = $request->no_gr;
        if ($request->file_evidence)
            $do_in->file_evidence = Storage::disk('public')->put('do_in', $request->file_evidence);
        if ($request->file_foto_terima)
            $do_in->file_foto_terima = Storage::disk('public')->put('do_in', $request->file_foto_terima);
        $do_in->save();
        $do_in->load('po');
        $data = new DoInResource($do_in);

        return response()->json(['data' => $data, 'message' => 'Success update data do in'], 200);
    }

    public function process(Request $request)
    {
        $do_in = DoIn::where('uuid', $request->id)->with(['item_do_in'])->first();
        if (!$do_in)
            return response()->json(['data' => $do_in, 'message' => 'Data not found'], 404);
        if ($request->status == "reject") {
            $do_in->status = "reject";
            $do_in->status_keterangan = $request->status_keterangan;
            $do_in->save();
            return response()->json(['data' => $do_in, 'message' => 'Success reject data do in'], 200);
        }

        $do_in->status = "approve";
        $do_in->keterangan_status = $request->keterangan_status;
        $do_in->save();
        $result = $this->addAsset($do_in);
        return response()->json(['data' => $do_in, 'message' => 'Success approve data do in'], 200);
    }

    private function addAsset($do_in)
    {
        // dd($do_in->item_do_in);
        // $existing_labels_count = Asset::where('id_asset', $request->id_asset)->count();
        // $start_index = $existing_labels_count + 1;
        // $plan = Plan::where('uuid', $request->id_asset)->first();
        foreach ($do_in->item_do_in as $item) {
            $asset = new Asset();
            $asset->uuid = Str::uuid();
            $asset->nama_barang = $item->nama;
            $asset->sn = $item->sn;
            $asset->jumlah = $item->jumlah;
            $asset->owner_id = $item->owner_id;
            $asset->do_in_id = $do_in->id;
            // $asset->label = $this->generateLabel($request->id_asset, $asset->quantity, $asset_label->internal_order, $start_index);
            $asset->label = "label";
            $asset->save();
        }
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
