<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetLabelRequest;
use App\Http\Requests\UpdateAssetLabelRequest;
use App\Imports\AssetLabelImport;
use App\Models\AssetLabel;
use App\Models\AssetRecap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class AssetLabelController extends Controller
{
    //Get By id
    public function getById(Request $request)
    {
        $asset_label = AssetLabel::where('id', $request->id)->first();
        if (!$asset_label)
            return response()->json(['data' => $asset_label, 'message' => 'Data not found'], 404);

        return response()->json(['data' => $asset_label, 'message' => 'Success get data asset label'], 200);
    }

    public function getByIdAsset(Request $request)
    {
        $asset_labels = AssetLabel::where('id_asset', $request->id_asset)->get();
        if (!$asset_labels)
            return response()->json(['data' => $asset_labels, 'message' => 'Data not found'], 404);

        return response()->json(['data' => $asset_labels, 'message' => 'Success get data asset label'], 200);
    }

    //Get All
    public function getAll()
    {
        $asset_recap = AssetRecap::withCount('assetLabels')->get();

        return response()->json(['data' => $asset_recap, 'message' => 'Success get data asset labels'], 200);
    }

    //Get Garbage
    public function getGarbage()
    {
        $asset_label = AssetLabel::onlyTrashed()->get();

        return response()->json(['data' => $asset_label, 'message' => 'Success get data asset label'], 200);
    }

    public function upload(Request $request)
    {
        try {
            // Find the asset record and get the count of existing labels.
            // It's more efficient to get the count directly.
            $asset = AssetRecap::where('id_asset', $request->id_asset)->first();
            $existing_labels_count = AssetLabel::where('id_asset', $request->id_asset)->count();
            $spreadsheet = Excel::toArray(new AssetLabelImport($asset), $request->file('file'));
            $count_data = count($spreadsheet[0]);

            // Validate the total quantity
            if ($count_data + $existing_labels_count > $asset->quantity) {
                return response()->json(['message' => 'Quantity not match'], 400);
            }

            // Import the file first
            $file = $request->file('file');
            Excel::import(new AssetLabelImport($asset), $file, \Maatwebsite\Excel\Excel::XLSX);

            // Get ALL the labels for the asset and sort them
            // This is a safer approach to get all records, then find the latest ones
            $all_asset_labels = AssetLabel::where('id_asset', $request->id_asset)->whereNull('label')
                ->orderBy('id', 'asc') // Sort all labels by ID
                ->get();

            // Start index from the total count of existing labels
            $start_index = $existing_labels_count + 1;

            // Loop through ALL labels and update the new ones
            foreach ($all_asset_labels as $index => $asset_label) {
                // Check if this is one of the newly added labels
                // A simple way is to check if the label column is empty
                if (empty($asset_label->label)) {
                    $asset_label->label = $this->generateLabel($request->id_asset, $asset->quantity, $asset_label->internal_order, $start_index);
                    $asset_label->save();
                    $start_index++; // Increment the index for the next new label
                }
            }

            return response()->json(['message' => 'Success upload data'], 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    "tracing" => $e->getTraceAsString()
                ],
                500
            );
        }
    }

    private function generateLabel($id_asset, $qty, $internal_order, $index)
    {
        return sprintf(
            "%s / %s / %04d-%04d",
            $internal_order,
            $id_asset,
            $qty,
            $index
        );
    }

    //Store data
    public function store(StoreAssetLabelRequest $request)
    {

        $labels = $this->generateLabels($request->id_asset, $request->qty, $request->internal_order);

        foreach ($labels as $label) {
            $asset_label = new AssetLabel();
            $asset_label->id = Str::id();
            $asset_label->id_asset = $request->id_asset;
            $asset_label->label = $label;
            $asset_label->internal_order = $request->internal_order;
            $asset_label->description_label = $request->description_label;
            $asset_label->save();
        }

        return response()->json(['data' => $request->id_asset, 'message' => 'Success store data asset label'], 200);
    }

    //Update data
    public function update(Request $request)
    {
        $asset_label = AssetLabel::where('id', $request->id)->first();
        if (!$asset_label)
            return response()->json(['data' => $asset_label, 'message' => 'Data not found'], 404);
        $request->validate((new UpdateAssetLabelRequest())->rules($asset_label));
        $asset_label->sn = $request->sn;
        $asset_label->condition = $request->condition;
        $asset_label->location_type = $request->location_type;
        $asset_label->lease_type = $request->lease_type;
        $asset_label->location_detail = $request->location_detail;
        $asset_label->owner = $request->owner;
        $asset_label->condition = $request->condition;
        $asset_label->is_active = strtolower($request->is_active) === 'aktif' ? true : false;
        $asset_label->address = $request->address;
        $asset_label->description = $request->description;
        $asset_label->description_label = $request->description_label;
        $asset_label->status_barcode = $request->status_barcode;

        if ($request->status_barcode) {
            $file_name = 'qrcodes/barcode_' . $asset_label->id . '.png';
            Storage::disk('public')->put($file_name, QrCode::format('png')->size(200)->generate($asset_label->label));
            $asset_label->barcode = $file_name;
        }

        $asset_label->update();

        return response()->json(['data' => $asset_label, 'message' => 'Success update data asset label'], 200);
    }

    //Delete data
    public function destroy(Request $request)
    {
        $model = AssetLabel::where('id', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);

        $model->delete();
        return response()->json(['message' => 'Success delete data asset label'], 200);
    }

    //Restore data softdelete
    public function restore(Request $request)
    {
        $model = AssetLabel::withTrashed()->where('id', $request->id)->first();
        if (!$model)
            return response()->json(['data' => $model, 'message' => 'Data not found'], 404);
        $model->restore();
        return response()->json(['data' => $model, 'message' => 'Success restore data asset label'], 200);
    }

    private function generateLabels($id_asset, $qty, $internal_order)
    {
        $codes = [];

        for ($i = 1; $i <= $qty; $i++) {
            $codes[] = sprintf(
                "%s/%s/%04d-%04d",
                $internal_order,
                $id_asset,
                $qty,
                $i
            );
        }

        return $codes;
    }

    public function download(Request $request)
    {
        $asset_labels = AssetLabel::where('id_asset', $request->id_asset)->where('status', 1)->get();

        // Lakukan perulangan untuk setiap aset untuk mengubah path barcode menjadi Base64
        foreach ($asset_labels as $asset) {
            // Pastikan file gambar ada di direktori public/storage atau lainnya
            $imagePath = public_path('storage/' . $asset->barcode);


            // Periksa apakah file ada sebelum mengonversinya
            if (file_exists($imagePath)) {
                // Baca file gambar dan ubah menjadi Base64
                $asset["print_barcode"] = 'data:image/png;base64,' . base64_encode(file_get_contents($imagePath));
            } else {
                // Tangani kasus di mana file tidak ditemukan, misalnya dengan gambar default
                $asset["print_barcode"] = null; // Atau ganti dengan gambar "not found"
            }
        }

        // Menggunakan library PDF untuk mengkonversi view menjadi PDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('print.asset_label', compact('asset_labels'));

        // Mengembalikan file PDF untuk diunduh
        return $pdf->download('label-aset-semua.pdf');
    }

    public function showDownload(Request $request)
    {
        $asset_labels = AssetLabel::where('id_asset', $request->id_asset)->get();
        // Lakukan perulangan untuk setiap aset untuk mengubah path barcode menjadi Base64
        foreach ($asset_labels as $asset) {
            // Pastikan file gambar ada di direktori public/storage atau lainnya
            $imagePath = public_path('storage/' . $asset->barcode);


            // Periksa apakah file ada sebelum mengonversinya
            if (file_exists($imagePath)) {
                // Baca file gambar dan ubah menjadi Base64
                $asset["print_barcode"] = 'data:image/png;base64,' . base64_encode(file_get_contents($imagePath));
            } else {
                // Tangani kasus di mana file tidak ditemukan, misalnya dengan gambar default
                $asset["print_barcode"] = null; // Atau ganti dengan gambar "not found"
            }
        }

        return view('print.asset_label', compact('asset_labels'));
    }
}
