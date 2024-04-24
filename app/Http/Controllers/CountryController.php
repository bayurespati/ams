<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    //Get By id
    public function getById(Request $request, Country $country)
    {
        return response()->json(['data' => $country, 'message' => 'Success get data country'], 200);
    }

    //Get All
    public function getAll()
    {
        $cities = Country::all();

        return response()->json(['data' => $cities, 'message' => 'Success get data countries'], 200);
    }

    //Store data
    public function store(StoreCountryRequest $request)
    {
        $country = new Country();
        $country->name = $request->name;
        $country->alias = $request->alias;
        $country->save();

        return response()->json(['data' => $country, 'message' => 'Success store data country'], 200);
    }

    //Update data
    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->name = $request->name;
        $country->alias = $request->alias;
        $country->save();

        return response()->json(['data' => $country, 'message' => 'Success update data country'], 200);
    }

    //Delete data
    public function destroy(country $country)
    {
        $country->delete();

        return response()->json(['message' => 'Success delete data country'], 200);
    }
}
