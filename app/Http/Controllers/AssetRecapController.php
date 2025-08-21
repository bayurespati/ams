<?php

namespace App\Http\Controllers;

use App\Models\AssetRecap;
use App\Imports\AssetRecapImport;
use Illuminate\Http\Request;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class AssetRecapController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $file = $request->file('file');
            Excel::import(new AssetRecapImport, $file, \Maatwebsite\Excel\Excel::XLSX);
            return response()->json(['message' => 'Success upload data'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request)
    {
        $assetRecap = AssetRecap::where('asset_id', $request->asset_id)->first();
        if (!$assetRecap) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $assetRecap->qty_location_customer = $request->qty_location_customer;
        $assetRecap->qty_location_pins = $request->qty_location_pins;
        $assetRecap->qty_location_warehouse = $request->qty_location_warehouse;
        $assetRecap->save();

        return response()->json(['data' => $assetRecap, 'message' => 'Success update data'], 200);
    }
}
