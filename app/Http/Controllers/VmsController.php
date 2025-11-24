<?php

namespace App\Http\Controllers;

use App\Models\VMS\PurchaseOrder;
use Illuminate\Http\Request;

class VmsController extends Controller
{
    public function getPo()
    {
        $po = PurchaseOrder::with('bakn')->get();
        return response()->json(['data' => $po, 'message' => 'Success get data po'], 200);
    }
}
