<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function getPlans()
    {

        $plans = Plan::with(['item_type', 'item_variety', 'po'])->get()->map(function ($plan) {
            return [
                'nama_barang' => $plan->nama_barang,
                "uuid" => $plan->uuid,
                "project_id" => $plan->project_id,
                "judul" => $plan->judul,
                "nama_barang" => $plan->nama_barang,
                "jumlah_barang" => $plan->jumlah_barang,
                "is_lop" => $plan->is_lop,
                "file_prpo" => $plan->file_prpo,
                "no_prpo" => $plan->no_prpo,
                'jenis_barang_id' => optional($plan->item_variety)->uuid,
                'tipe_barang_id' => optional($plan->item_type)->uuid,
            ];
        });
        return response()->json(['data' => $plans, 'message' => 'Success get data plans'], 200);
    }
}
