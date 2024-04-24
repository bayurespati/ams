<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    //Get By id
    public function getById(Request $request, City $city)
    {
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
        $city->name = $request->name;
        $city->alias = $request->alias;
        $city->save();

        return response()->json(['data' => $city, 'message' => 'Success store data city'], 200);
    }

    //Update data
    public function update(UpdateCityRequest $request, City $city)
    {
        $city->name = $request->name;
        $city->alias = $request->alias;
        $city->save();

        return response()->json(['data' => $city, 'message' => 'Success update data city'], 200);
    }

    //Delete data
    public function destroy(City $city)
    {
        $city->delete();

        return response()->json(['message' => 'Success delete data city'], 200);
    }
}
