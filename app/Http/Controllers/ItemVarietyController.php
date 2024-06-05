<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemVarietyRequest;
use App\Http\Requests\UpdateItemVarietyRequest;
use App\Models\ItemVariety;
use Illuminate\Http\Request;

class ItemVarietyController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $item_variety = ItemVariety::find($request->id);
        if (!$item_variety)
            return response()->json(['data' => $item_variety, 'message' => 'Data not found'], 404);
        return response()->json(['data' => $item_variety, 'message' => 'Success get data item variety'], 200);
    }

    //Get All
    public function getAll()
    {
        $item_variety = ItemVariety::all();

        return response()->json(['data' => $item_variety, 'message' => 'Success get data item varieties'], 200);
    }

    //Store data
    public function store(StoreItemVarietyRequest $request)
    {
        $item_variety = new ItemVariety();
        $item_variety->nama = $request->nama;
        $item_variety->save();

        return response()->json(['data' => $item_variety, 'message' => 'Success store data item variety'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $item_variety = ItemVariety::find($request->id);
        if (!$item_variety)
            return response()->json(['data' => $item_variety, 'message' => 'Data not found'], 404);
        $request->validate((new UpdateItemVarietyRequest())->rules($item_variety));
        $item_variety->nama = $request->nama;
        $item_variety->save();

        return response()->json(['data' => $item_variety, 'message' => 'Success update data item variety'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = ItemVariety::find($request->id);
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data item variety'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = ItemVariety::withTrashed()->find($request->id);
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model = ItemVariety::withTrashed()->find($request->id)->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data item variety'], 200);
    }
}
