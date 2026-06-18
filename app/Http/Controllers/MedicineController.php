<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::with(['category', 'supplier'])
            ->orderBy('name', 'asc')
            ->get();
            
        return view('medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|unique:medicines,barcode',
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'dosage_form' => 'required|string',
            'strength' => 'required|string',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
            'description' => 'nullable|string',
        ]);
        
        Medicine::create($request->all());
        
        return redirect()->route('medicines.index')
            ->with('success', 'Obat berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $medicine = Medicine::findOrFail($id);
        $categories = Category::all();
        $suppliers = Supplier::all();
        
        return view('medicines.edit', compact('medicine', 'categories', 'suppliers'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'barcode' => 'required|unique:medicines,barcode,' . $id,
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'dosage_form' => 'required|string',
            'strength' => 'required|string',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
            'description' => 'nullable|string',
        ]);
        
        $medicine = Medicine::findOrFail($id);
        $medicine->update($request->all());
        
        return redirect()->route('medicines.index')
            ->with('success', 'Data obat berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        try {
            $medicine = Medicine::findOrFail($id);
            $medicine->delete();
            
            return redirect()->route('medicines.index')
                ->with('success', 'Obat berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check for foreign key constraint violation (SQLSTATE 23000)
            if ($e->getCode() == '23000') {
                return redirect()->route('medicines.index')
                    ->with('error', 'Gagal menghapus! Obat ini sudah terikat dengan riwayat transaksi kasir.');
            }
            
            return redirect()->route('medicines.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}