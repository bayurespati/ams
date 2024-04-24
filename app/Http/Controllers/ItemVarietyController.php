<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemVarietyRequest;
use App\Http\Requests\UpdateItemVarietyRequest;
use App\Models\ItemVariety;
use Illuminate\Http\Request;

class ItemVarietyController extends Controller
{
    //Get By id
    public function getById(Request $request, ItemVariety $item_variety)
    {
        return response()->json(['data' => $item_variety, 'message' => 'Success get data item variety'], 200);
    }

    //Get All
    public function getAll()
    {
        $item_types = ItemVariety::all();

        return response()->json(['data' => $item_types, 'message' => 'Success get data item varieties'], 200);
    }

    //Store data
    public function store(StoreItemVarietyRequest $request)
    {
        $item_type = new ItemVariety();
        $item_type->name = $request->name;
        $item_type->save();

        return response()->json(['data' => $item_type, 'message' => 'Success store data item variety'], 200);
    }

    //Update data
    public function update(UpdateItemVarietyRequest $request, ItemVariety $item_variety)
    {
        $item_variety->name = $request->name;
        $item_variety->save();

        return response()->json(['data' => $item_variety, 'message' => 'Success update data item variety'], 200);
    }

    //Delete data
    public function destroy(ItemVariety $item_variety)
    {
        $item_variety->delete();

        return response()->json(['message' => 'Success delete data item variety'], 200);
    }
}
