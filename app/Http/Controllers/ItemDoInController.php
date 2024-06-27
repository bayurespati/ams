<?php

namespace App\Http\Controllers;

use App\Imports\ItemDoInImport;
use App\Models\DoIn;
use App\Models\ItemDoIn;
use Illuminate\Http\Request;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class ItemDoInController extends Controller
{
    //Get All
    public function getAll()
    {
        $do_in = ItemDoIn::all();

        return response()->json(['data' => $do_in, 'message' => 'Success get data item do in'], 200);
    }

    //Add item do in with upload
    public function uploadItem(Request $request)
    {
        $do = DoIn::find($request->do_in_id);
        if (!$do)
            return response()->json(['message' => 'Data do in not found'], 404);

        try {

            $file = $request->file('file');
            Excel::import(new ItemDoInImport($request->do_in_id), $file, \Maatwebsite\Excel\Excel::XLSX);

            return response()->json(['data' => $do, 'message' => 'Success add item on do in'], 200);
        } catch (Exception $e) {
            return response()->json(['data' => $do, 'message' => $e], 500);
        }
    }

    //Add item do in on json
    public function addItem(Request $request)
    {
        $do = DoIn::find($request->do_in_id);
        if (!$do)
            return response()->json(['message' => 'Data do in not found'], 404);

        try {
            foreach ($request->items as $item) {
                $itemDoIn = new ItemDoIn();
                $itemDoIn->do_in_id = $request->do_in_id;
                $itemDoIn->sn = $item["sn"];
                $itemDoIn->jumlah = $item["jumlah"];
                $itemDoIn->save();
            }
            return response()->json(['data' => $do, 'message' => 'Success add item on do in'], 200);
        } catch (Exception $e) {
            return response()->json(['data' => $do, 'message' => $e], 500);
        }
    }

    //Update data
    public function verification(Request $request)
    {
        $item_do_in = ItemDoIn::where('do_in_id', $request->do_in_id)->where('id', $request->id)->first();
        if (!$item_do_in)
            return response()->json(['data' => $item_do_in, 'message' => 'Data not found'], 404);
        $item_do_in->is_verified = true;
        $item_do_in->save();

        return response()->json(['data' => $item_do_in, 'message' => 'Success verification data item do in'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $item_do_in = ItemDoIn::find($request->id);
        if (!$item_do_in)
            return response()->json(['data' => $item_do_in, 'message' => 'Data not found'], 404);
        $item_do_in->sn = $request->sn;
        $item_do_in->jumlah = $request->jumlah;
        $item_do_in->save();

        return response()->json(['data' => $item_do_in, 'message' => 'Success update data item do in'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = ItemDoIn::find($request->id);
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data item do in'], 200);
    }
}
