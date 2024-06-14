<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CountryController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $country = Country::where('uuid', $request->id)->first();
        if (!$country)
            return response()->json(['data' => $country, 'message' => 'Data not found'], 404);

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
        $country->uuid = Str::uuid();
        $country->nama = $request->nama;
        $country->alias = $request->alias;
        $country->save();

        return response()->json(['data' => $country, 'message' => 'Success store data country'], 200);
    }

    //Get Garbage
    public function getGarbage()
    {
        $country = Country::onlyTrashed()->get();

        return response()->json(['data' => $country, 'message' => 'Success get data garbage country'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $country = Country::where('uuid', $request->id)->first();
        if (!$country)
            return response()->json(['data' => $country, 'message' => 'Data not found'], 404);
        $request->validate((new UpdateCountryRequest())->rules($country));
        $country->nama = $request->nama;
        $country->alias = $request->alias;
        $country->update();

        return response()->json(['data' => $country, 'message' => 'Success update data country'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = Country::where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data country'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = Country::withTrashed()->where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data country'], 200);
    }
}
