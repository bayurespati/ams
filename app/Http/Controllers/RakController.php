<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRakRequest;
use App\Http\Requests\UpdateRakRequest;
use App\Models\Rak;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RakController extends Controller
{
    // Get By id
    public function getById(Request $request)
    {
        $rak = Rak::where('uuid', $request->id)->first();
        if (!$rak) {
            return response()->json(['data' => null, 'message' => 'Rak not found'], 404);
        }
        return response()->json(['data' => $rak, 'message' => 'Success get rak'], 200);
    }

    // Get All
    public function getAll()
    {
        $rak = Rak::all()->map(function ($rak) {
            return [
                'uuid' => $rak->uuid,
                'kode_rak' => $rak->kode_rak,
                'nama_rak' => $rak->nama_rak,
                'baris_rak' => $rak->baris_rak,
                'kolom_rak' => $rak->kolom_rak,
                'lokasi_rak' => $rak->lokasi_rak,
                'kapasitas_rak' => $rak->kapasitas_rak,
                'status_rak' => $rak->status_rak,
                'keterangan' => $rak->keterangan,
                'created_at' => $rak->created_at,
            ];
        });

        return response()->json(['data' => $rak, 'message' => 'Success get rak'], 200);
    }

    // Get Garbage (Soft Deleted)
    public function getGarbage()
    {
        $rak = Rak::onlyTrashed()->get();
        return response()->json(['data' => $rak, 'message' => 'Success get deleted rak'], 200);
    }

    // Store data
    public function store(StoreRakRequest $request)
    {
        $rak = new Rak();
        $rak->uuid = (string) Str::uuid();
        $rak->kode_rak = $request->kode_rak;
        $rak->nama_rak = $request->nama_rak;
        $rak->baris_rak = $request->baris_rak;
        $rak->kolom_rak = $request->kolom_rak;
        $rak->lokasi_rak = $request->lokasi_rak;
        $rak->kapasitas_rak = $request->kapasitas_rak;
        $rak->status_rak = $request->status_rak;
        $rak->keterangan = $request->keterangan ?? null;

        $rak->save();

        return response()->json(['data' => $rak, 'message' => 'Success create rak'], 201);
    }

    // Update data
    public function update(Request $request)
    {
        $rak = Rak::where('uuid', $request->id)->first();
        if (!$rak) {
            return response()->json(['data' => null, 'message' => 'Rak not found'], 404);
        }

        $request->validate((new UpdateRakRequest())->rules($rak));

        $rak->kode_rak = $request->kode_rak;
        $rak->nama_rak = $request->nama_rak;
        $rak->baris_rak = $request->baris_rak;
        $rak->kolom_rak = $request->kolom_rak;
        $rak->lokasi_rak = $request->lokasi_rak;
        $rak->kapasitas_rak = $request->kapasitas_rak;
        $rak->status_rak = $request->status_rak;
        $rak->keterangan = $request->keterangan ?? $rak->keterangan;

        $rak->save();

        return response()->json(['data' => $rak, 'message' => 'Success update rak'], 200);
    }

    // Delete data
    public function destroy(Request $request)
    {
        $rak = Rak::where('uuid', $request->id)->first();
        if (!$rak) {
            return response()->json(['data' => null, 'message' => 'Rak not found'], 404);
        }

        $rak->delete();
        return response()->json(['message' => 'Success delete rak'], 200);
    }

    // Restore soft deleted data
    public function restore(Request $request)
    {
        $rak = Rak::withTrashed()->where('uuid', $request->id)->first();
        if (!$rak) {
            return response()->json(['data' => null, 'message' => 'Rak not found'], 404);
        }

        $rak->restore();
        return response()->json(['data' => $rak, 'message' => 'Success restore rak'], 200);
    }
}