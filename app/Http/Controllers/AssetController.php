<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Http\Resources\AssetResource;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Rak;

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

    public function update(Request $request)
    {
        $asset = Asset::where('uuid', $request->id)->first();
        $rak = Rak::where('uuid', $request->rack_id)->first();
        if (!$asset)
            return response()->json(['data' => $asset, 'message' => 'Data not found'], 404);
        if (!$rak)
            return response()->json(['message' => 'Data rack not found'], 404);
        $asset->rak_id = $rak->id;
        $asset->save();
        $asset->load('do_in');
        $data = new AssetResource($asset);
        return response()->json(['data' => $data, 'message' => 'Success update data asset'], 200);
    }
}
