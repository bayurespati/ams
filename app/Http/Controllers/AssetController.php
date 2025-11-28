<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Http\Resources\AssetResource;
use Illuminate\Http\Request;
use App\Models\Asset;

class AssetController extends Controller
{
    public function getById(Request $request)
    {
        //Test git branch
        $asset = Asset::where('uuid', $request->id)->with(['do_in', 'owner'])->first();
        if (!$asset) {
            return response()->json(['data' => $asset, 'message' => 'Data not found'], 404);
        }
        $data = new AssetResource($asset);
        return response()->json(['data' => $data, 'message' => 'Success get data do in'], 200);
    }
    //Get All
    public function getAll()
    {
        $asset = Asset::with(['do_in', 'owner'])->get();
        $data = AssetResource::collection($asset);
        return response()->json(['data' => $data, 'message' => 'Success get data asset'], 200);
    }
}
