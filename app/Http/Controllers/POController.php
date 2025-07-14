<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePORequest;
use App\Http\Requests\UpdatePORequest;
use App\Models\Plan;
use App\Models\PO;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class POController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $po = PO::where('uuid', $request->id)->first();
        if (!$po)
            return response()->json(['data' => $po, 'message' => 'Data not found'], 404);
        return response()->json(['data' => $po, 'message' => 'Success get data po'], 200);
    }

    //Get All
    public function getAll()
    {
        $pos = PO::with(['plan'])->get()->map(function ($po) {
            return [
                "uuid" => $po->uuid,
                "nama_pekerjaan" => $po->nama_pekerjaan,
                "no_po_spk_pks" => $po->no_po_spk_pks,
                "tanggal_po_spk_pks" => $po->tanggal_po_spk_pks,
                "file_po_spk_pks" => $po->file_po_spk_pks,
                "file_boq" => $po->file_boq,
                "nilai_pengadaan" => $po->nilai_pengadaan,
                "tanggal_delivery" => $po->tanggal_delivery,
                "akun" => $po->akun,
                "cost_center" => $po->cost_center,
                'plan_id' => optional($po->plan)->uuid,
            ];
        });

        return response()->json(['data' => $pos, 'message' => 'Success get data po'], 200);
    }

    //Get Garbage
    public function getGarbage()
    {
        $po = PO::onlyTrashed()->get();

        return response()->json(['data' => $po, 'message' => 'Success get data garbage po'], 200);
    }

    //Store data
    public function store(StorePORequest $request)
    {
        $plan = Plan::where('uuid', $request->plan_id)->first();
        if (!$plan)
            return response()->json(['message' => 'Data plan not found'], 404);
        $po = new PO();
        $po->uuid = Str::uuid();
        $po->plan_id = $plan->id;
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
        //check plan id before update data po
        $plan = Plan::where('uuid', $request->plan_id)->first();
        if (!$plan)
            return response()->json(['message' => 'Data plan not found'], 404);
        $po = PO::where('uuid', $request->id)->first();
        if (!$po)
            return response()->json(['data' => $po, 'message' => 'Data not found'], 404);

        //validate data
        $request->validate((new UpdatePORequest())->rules($po));

        $po->plan_id = $plan->id;
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
    public function destroy(Request $request)
    {
        $model = PO::where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data po'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = PO::withTrashed()->where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data po'], 200);
    }
}
