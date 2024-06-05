<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemTypeRequest;
use App\Http\Requests\UpdateItemTypeRequest;
use App\Models\ItemType;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $item_type = ItemType::find($request->id);
        if (!$item_type)
            return response()->json(['data' => $item_type, 'message' => 'Data not found'], 404);
        return response()->json(['data' => $item_type, 'message' => 'Success get data item type'], 200);
    }

    //Get All
    public function getAll()
    {
        $item_types = ItemType::all();

        return response()->json(['data' => $item_types, 'message' => 'Success get data item types'], 200);
    }

    //Store data
    public function store(StoreItemTypeRequest $request)
    {
        $item_type = new ItemType();
        $item_type->nama = $request->nama;
        $item_type->save();

        return response()->json(['data' => $item_type, 'message' => 'Success store data item type'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $item_type = ItemType::find($request->id);
        if (!$item_type)
            return response()->json(['data' => $item_type, 'message' => 'Data not found'], 404);
        $request->validate((new UpdateItemTypeRequest())->rules($item_type));
        $item_type->nama = $request->nama;
        $item_type->save();

        return response()->json(['data' => $item_type, 'message' => 'Success update data item type'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = ItemType::find($request->id);
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data item type'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = ItemType::withTrashed()->find($request->id);
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model = ItemType::withTrashed()->find($request->id)->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data item type'], 200);
    }
}
