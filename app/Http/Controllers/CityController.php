<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $city = City::where('uuid', $request->id)->first();
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
        $city->uuid = Str::uuid();
        $city->nama = $request->nama;
        $city->alias = $request->alias;
        $city->save();

        return response()->json(['data' => $city, 'message' => 'Success store data city'], 200);
    }

    //Get Garbage
    public function getGarbage()
    {
        $cities = City::onlyTrashed()->get();

        return response()->json(['data' => $cities, 'message' => 'Success get data garbage  cities'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $city = City::where('uuid', $request->id)->first();
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
        $model = City::where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data city'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = City::withTrashed()->where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data city'], 200);
    }
}
