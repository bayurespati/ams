<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $city = City::find($request->id);
        if (!$city)
            return response()->json(['data' => $city, 'message' => 'Data not found'], 404);
        return response()->json(['data' => $city, 'message' => 'Success get data city'], 200);
    }

    //Get All
    public function getAll()
    {
        $cities = City::all();

        return response()->json(['data' => $cities, 'message' => 'Success get data cities'], 200);
    }

    //Store data
    public function store(StoreCityRequest $request)
    {
        $city = new City();
        $city->nama = $request->nama;
        $city->alias = $request->alias;
        $city->save();

        return response()->json(['data' => $city, 'message' => 'Success store data city'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $city = City::find($request->id);
        if (!$city)
            return response()->json(['data' => $city, 'message' => 'Data not found'], 404);
        $request->validate((new UpdateCityRequest())->rules($city));
        $city->nama = $request->nama;
        $city->alias = $request->alias;
        $city->save();

        return response()->json(['data' => $city, 'message' => 'Success update data city'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = City::find($request->id);
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data city'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = City::withTrashed()->find($request->id);
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model = City::withTrashed()->find($request->id)->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data city'], 200);
    }
}
