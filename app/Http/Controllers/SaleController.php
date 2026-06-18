<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Medicine;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'user', 'items.medicine']);

        // 1. Search filter (invoice, cashier, customer)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('customer', function($qc) use ($search) {
                      $qc->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }

        // 3. Date range filter
        if ($request->filled('date_range')) {
            $dateRange = $request->input('date_range');
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('created_at', '>=', today()->subDays(7));
                    break;
                case 'month':
                    $query->whereMonth('created_at', today()->month)
                          ->whereYear('created_at', today()->year);
                    break;
                case 'custom':
                    if ($request->filled('start_date')) {
                        $query->whereDate('created_at', '>=', $request->input('start_date'));
                    }
                    if ($request->filled('end_date')) {
                        $query->whereDate('created_at', '<=', $request->input('end_date'));
                    }
                    break;
            }
        }

        // Hitung statistik berdasarkan data terfilter yang Selesai (completed)
        $statsQuery = clone $query;
        $statsQuery->where('status', 'completed');
        
        $totalRevenue = $statsQuery->sum('total_amount');
        $totalTransactions = $statsQuery->count();
        $averageOrderValue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        $sales = $query->orderBy('created_at', 'desc')->get();
            
        return view('sales.index', compact('sales', 'totalRevenue', 'totalTransactions', 'averageOrderValue'));
    }

    public function create()
    {
        $medicines = Medicine::where('stock', '>', 0)
            ->orderBy('name', 'asc')
            ->get();
            
        $customers = Customer::orderBy('name', 'asc')->get();
        
        return view('sales.create', compact('medicines', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,cashless',
            'payment_amount' => 'required|numeric|min:0',
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $itemsData = [];

            // 1. Hitung total dan validasi stok terlebih dahulu
            foreach ($request->items as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);
                
                if ($medicine->stock < $item['quantity']) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Stok obat '{$medicine->name}' tidak mencukupi (Tersedia: {$medicine->stock}).");
                }

                $subtotal = $medicine->selling_price * $item['quantity'];
                $totalAmount += $subtotal;

                $itemsData[] = [
                    'medicine_id' => $medicine->id,
                    'quantity' => $item['quantity'],
                    'price' => $medicine->selling_price,
                    'subtotal' => $subtotal,
                    'model' => $medicine
                ];
            }

            // 2. Validasi jumlah pembayaran
            $paymentAmount = $request->payment_amount;
            if ($paymentAmount < $totalAmount) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Pembayaran kurang! Total transaksi: Rp " . number_format($totalAmount, 0, ',', '.') . ", dibayarkan: Rp " . number_format($paymentAmount, 0, ',', '.'));
            }

            // 3. Simpan data penjualan
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            $changeAmount = $paymentAmount - $totalAmount;
            
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id() ?? User::first()->id ?? 1, // Default ke user ID 1 (Admin) jika tidak login
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_amount' => $paymentAmount,
                'change_amount' => $changeAmount,
                'status' => 'completed'
            ]);

            // 4. Kurangi stok obat & simpan item penjualan
            foreach ($itemsData as $itemData) {
                $medicine = $itemData['model'];
                
                // Kurangi stok database
                $medicine->decrement('stock', $itemData['quantity']);

                // Simpan ke sale_items
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $itemData['medicine_id'],
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'subtotal' => $itemData['subtotal']
                ]);
            }

            DB::commit();

            return redirect()->route('sales.show', $sale->id)
                ->with('success', 'Transaksi penjualan berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $sale = Sale::with(['items.medicine', 'customer', 'user'])
            ->findOrFail($id);
            
        return view('sales.show', compact('sale'));
    }

    public function json($id)
    {
        $sale = Sale::with(['items.medicine', 'customer', 'user'])->find($id);
        
        if (!$sale) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sale
        ]);
    }

    public function void($id)
    {
        try {
            DB::beginTransaction();

            $sale = Sale::findOrFail($id);

            if ($sale->status === 'cancelled') {
                return redirect()->back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
            }

            // Kembalikan stok obat
            $saleItems = SaleItem::where('sale_id', $sale->id)->get();
            foreach ($saleItems as $item) {
                $medicine = Medicine::find($item->medicine_id);
                if ($medicine) {
                    $medicine->increment('stock', $item->quantity);
                }
            }

            // Ubah status transaksi menjadi cancelled
            $sale->status = 'cancelled';
            $sale->save();

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil dibatalkan dan stok obat telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $query = Sale::with(['customer', 'user']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('customer', function($qc) use ($search) {
                      $qc->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }

        if ($request->filled('date_range')) {
            $dateRange = $request->input('date_range');
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('created_at', '>=', today()->subDays(7));
                    break;
                case 'month':
                    $query->whereMonth('created_at', today()->month)
                          ->whereYear('created_at', today()->year);
                    break;
                case 'custom':
                    if ($request->filled('start_date')) {
                        $query->whereDate('created_at', '>=', $request->input('start_date'));
                    }
                    if ($request->filled('end_date')) {
                        $query->whereDate('created_at', '<=', $request->input('end_date'));
                    }
                    break;
            }
        }

        $sales = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'laporan-penjualan-' . date('Y-m-d-His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($sales) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            fputcsv($file, [
                'No. Invoice',
                'Tanggal',
                'Waktu',
                'Kasir',
                'Pelanggan',
                'Metode Pembayaran',
                'Status',
                'Total Amount (Rp)'
            ]);

            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->invoice_number,
                    $sale->created_at->format('d-m-Y'),
                    $sale->created_at->format('H:i'),
                    $sale->user->name ?? 'Admin Apotek',
                    $sale->customer->name ?? 'Umum / Non-Member',
                    $sale->payment_method === 'cash' ? 'Tunai' : 'Non-Tunai',
                    $sale->status === 'cancelled' ? 'Dibatalkan' : 'Selesai',
                    $sale->total_amount
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
