<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemTypeRequest;
use App\Http\Requests\UpdateItemTypeRequest;
use App\Models\ItemType;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    //Get By id
    public function getById(Request $request, ItemType $item_type)
    {
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
        $item_type->name = $request->name;
        $item_type->save();

        return response()->json(['data' => $item_type, 'message' => 'Success store data item type'], 200);
    }

    //Update data
    public function update(UpdateItemTypeRequest $request, ItemType $item_type)
    {
        $item_type->name = $request->name;
        $item_type->save();

        return response()->json(['data' => $item_type, 'message' => 'Success update data item type'], 200);
    }

    //Delete data
    public function destroy(ItemType $item_type)
    {
        $item_type->delete();

        return response()->json(['message' => 'Success delete data item type'], 200);
    }
}
