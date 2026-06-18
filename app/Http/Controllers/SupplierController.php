<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function storeAjax(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'supplier' => $supplier
        ]);
    }
}
