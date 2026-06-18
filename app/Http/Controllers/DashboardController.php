<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMedicines = Medicine::count();
        $todayTransactions = Sale::whereDate('created_at', date('Y-m-d'))->count();
        $todayRevenue = Sale::whereDate('created_at', date('Y-m-d'))->sum('total_amount');
        
        // Count medicines with stock <= min_stock
        $lowStock = Medicine::whereRaw('stock <= min_stock')->count();
        
        $recentSales = Sale::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalMedicines',
            'todayTransactions',
            'todayRevenue',
            'lowStock',
            'recentSales'
        ));
    }
}