<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePORequest;
use App\Http\Requests\UpdatePORequest;
use App\Models\Plan;
use App\Models\PO;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class POController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $po = PO::find($request->id);
        if (!$po)
            return response()->json(['data' => $po, 'message' => 'Data not found'], 404);
        return response()->json(['data' => $po, 'message' => 'Success get data po'], 200);
    }

    //Get All
    public function getAll()
    {
        $po = PO::all();

        return response()->json(['data' => $po, 'message' => 'Success get data po'], 200);
    }

    //Store data
    public function store(StorePORequest $request)
    {
        $plan = Plan::find($request->plan_id);
        if (!$plan)
            return response()->json(['message' => 'Data plan not found'], 404);
        $po = new PO();
        $po->plan_id = $request->plan_id;
        $po->nama_pekerjaan = $request->nama_pekerjaan;
        $po->no_po_spk_pks = $request->no_po_spk_pks;
        $po->tanggal_po_spk_pks = $request->tanggal_po_spk_pks;
        $po->file_boq = Storage::disk('public')->put('po', $request->file_boq);
        $po->nilai_pengadaan = $request->nilai_pengadaan;
        $po->tanggal_delivery = $request->tanggal_delivery;
        $po->akun = $request->akun;
        $po->cost_center = $request->cost_center;
        if ($request->file_po_spk_pks)
            $po->file_po_spk_pks = Storage::disk('public')->put('po', $request->file_po_spk_pks);
        $po->save();

        return response()->json(['data' => $po, 'message' => 'Success store data po'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $plan = Plan::find($request->plan_id);
        if (!$plan)
            return response()->json(['message' => 'Data plan not found'], 404);
        $po = PO::find($request->id);
        if (!$po)
            return response()->json(['data' => $po, 'message' => 'Data not found'], 404);
        $request->validate((new UpdatePORequest())->rules($po));
        $po->plan_id = $request->plan_id;
        $po->nama_pekerjaan = $request->nama_pekerjaan;
        $po->no_po_spk_pks = $request->no_po_spk_pks;
        $po->tanggal_po_spk_pks = $request->tanggal_po_spk_pks;
        $po->nilai_pengadaan = $request->nilai_pengadaan;
        $po->tanggal_delivery = $request->tanggal_delivery;
        $po->akun = $request->akun;
        $po->cost_center = $request->cost_center;
        if ($request->file_boq)
            $po->file_boq = Storage::disk('public')->put('po', $request->file_boq);
        if ($request->file_po_spk_pks)
            $po->file_po_spk_pks = Storage::disk('public')->put('po', $request->file_po_spk_pks);
        $po->save();

        return response()->json(['data' => $po, 'message' => 'Success update data po'], 200);
    }

    //Delete data
    public function destroy(PO $po)
    {
        $po->delete();
        return response()->json(['message' => 'Success delete data po'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = PO::withTrashed()->find($request->id);
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model = PO::withTrashed()->find($request->id)->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data po'], 200);
    }
}
