<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $company = Company::where('uuid', $request->id)->first();
        if (!$company)
            return response()->json(['data' => $company, 'message' => 'Data not found'], 404);

        return response()->json(['data' => $company, 'message' => 'Success get data company'], 200);
    }

    //Get All
    public function getAll()
    {
        $companies = Company::all();

        return response()->json(['data' => $companies, 'message' => 'Success get data companies'], 200);
    }

    //Get Garbage
    public function getGarbage()
    {
        $company = Company::onlyTrashed()->get();

        return response()->json(['data' => $company, 'message' => 'Success get data garbage company'], 200);
    }

    //Store data
    public function store(StoreCompanyRequest $request)
    {
        $company = new Company();
        $company->uuid = Str::uuid();
        $company->name = $request->name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->address = $request->address;
        $company->save();

        return response()->json(['data' => $company, 'message' => 'Success store data company'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $company = Company::where('uuid', $request->id)->first();
        if (!$company)
            return response()->json(['data' => $company, 'message' => 'Data not found'], 404);
        $request->validate((new UpdateCompanyRequest())->rules($company));
        $company->name = $request->name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->address = $request->address;
        $company->update();

        return response()->json(['data' => $company, 'message' => 'Success update data company'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = Company::where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data company'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = Company::withTrashed()->where('uuid', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data company'], 200);
    }
}
