<?php

namespace App\Http\Controllers;

use App\Models\Star\LOP;
use Illuminate\Http\Request;

class StarController extends Controller
{
    public function getProject()
    {
        $projects = LOP::where('won_date', '!=', NULL)->pluck('id', 'lop_nama')->toArray();
        return response()->json(['data' => $projects, 'message' => 'Success get data project'], 200);
    }
}
