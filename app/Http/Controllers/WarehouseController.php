<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $warehouse = Warehouse::where('uuid', $request->id)->first();
        $data = new WarehouseResource($warehouse);
        if (!$warehouse)
            return response()->json(['data' => $warehouse, 'message' => 'Data not found'], 404);
        return response()->json(['data' => $data, 'message' => 'Success get data warehouse'], 200);
    }

    //Get All
    public function getAll()
    {
        $warehouse = Warehouse::with(['country', 'city'])->get();
        $data = WarehouseResource::collection($warehouse);
        return response()->json(['data' => $data, 'message' => 'Success get data warehouse'], 200);
    }

    //Get Garbage
    public function getGarbage()
    {
        $warehouse = Warehouse::onlyTrashed()->get();
        $data = WarehouseResource::collection($warehouse);
        return response()->json(['data' => $data, 'message' => 'Success get data garbage warehouse'], 200);
    }

    //Store data
    public function store(StoreWarehouseRequest $request)
    {
        $country = Country::where('uuid', $request->country_id)->first();
        if (!$country)
            return response()->json(['message' => 'Data country not found'], 404);
        $city = City::where('uuid', $request->city_id)->first();
        if (!$city)
            return response()->json(['message' => 'Data city not found'], 404);
        $warehouse = new Warehouse();
        $warehouse->uuid = Str::uuid();
        $warehouse->country_id = $country->id;
        $warehouse->city_id = $city->id;
        $warehouse->name = $request->name;
        $warehouse->address = $request->address;
        $warehouse->save();

        return response()->json(['data' => $warehouse, 'message' => 'Success store data warehouse'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        //check id before update data
        $warehouse = Warehouse::where('uuid', "=", $request->id)->first();
        $country = Country::where('uuid', $request->country_id)->first();
        if (!$country)
            return response()->json(['message' => 'Data country not found'], 404);
        $city = City::where('uuid', $request->city_id)->first();
        if (!$city)
            return response()->json(['message' => 'Data city not found'], 404);

        //validate data
        $request->validate((new UpdateWarehouseRequest())->rules($warehouse));

        //update data
        $warehouse->country_id = $country->id;
        $warehouse->city_id = $city->id;
        $warehouse->name = $request->name;
        $warehouse->address = $request->address;
        $warehouse->save();

        return response()->json(['data' => $warehouse, 'message' => 'Success update data warehouse'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = Warehouse::where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data warehouse'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = Warehouse::withTrashed()->where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data warehouse'], 200);
    }
}
