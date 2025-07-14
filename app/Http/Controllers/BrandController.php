<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $brand = Brand::where('uuid', $request->id)->first();
        if (!$brand)
            return response()->json(['data' => $brand, 'message' => 'Data not found'], 404);

        return response()->json(['data' => $brand, 'message' => 'Success get data brand'], 200);
    }

    //Get All
    public function getAll()
    {
        $brands = Brand::all();

        return response()->json(['data' => $brands, 'message' => 'Success get data brands'], 200);
    }

    //Get Garbage
    public function getGarbage()
    {
        $brand = Brand::onlyTrashed()->get();

        return response()->json(['data' => $brand, 'message' => 'Success get data garbage brand'], 200);
    }

    //Store data
    public function store(StorebrandRequest $request)
    {
        $brand = new Brand();
        $brand->uuid = Str::uuid();
        $brand->name = $request->name;
        $brand->alias = $request->alias;
        $brand->save();

        return response()->json(['data' => $brand, 'message' => 'Success store data brand'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $brand = Brand::where('uuid', $request->id)->first();
        if (!$brand)
            return response()->json(['data' => $brand, 'message' => 'Data not found'], 404);
        $request->validate((new UpdatebrandRequest())->rules($brand));
        $brand->name = $request->name;
        $brand->alias = $request->alias;
        $brand->update();

        return response()->json(['data' => $brand, 'message' => 'Success update data brand'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = Brand::where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data brand'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = Brand::withTrashed()->where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data brand'], 200);
    }
}
