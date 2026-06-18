@extends('layouts.app-simple')

@section('styles')
<style>
    .gradient-banner {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: var(--radius-lg);
        color: white;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }
    
    .gradient-banner::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        top: -100px;
        right: -100px;
    }

    .stat-card {
        border: none;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .action-card {
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .action-card:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-md);
        transform: scale(1.01);
    }
</style>
@endsection

@section('content')
<!-- Banner Selamat Datang -->
<div class="gradient-banner">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="fw-extrabold mb-2" style="letter-spacing: -0.03em;">🏥 Apotek Super Canggih</h1>
            <p class="mb-0 opacity-75 fs-5">Selamat datang kembali! Kelola data obat, pantau stok, dan layani transaksi kasir dengan antarmuka yang modern, cepat, dan responsif.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <span class="badge bg-white text-dark py-2 px-3 fw-bold fs-6">
                <i class="bi bi-calendar3 text-primary"></i> {{ date('d M Y') }}
            </span>
        </div>
    </div>
</div>

<!-- Grid Statistik -->
<div class="row mb-4">
    <!-- Stat 1 -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted-custom fw-semibold d-block" style="font-size: 0.85rem;">Total Obat</span>
                    <h3 class="fw-bold mb-0 mt-1">{{ $totalMedicines }}</h3>
                </div>
                <div class="stat-icon bg-primary-light text-primary">
                    <i class="bi bi-capsule"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Stat 2 -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted-custom fw-semibold d-block" style="font-size: 0.85rem;">Transaksi Hari Ini</span>
                    <h3 class="fw-bold mb-0 mt-1">{{ $todayTransactions }}</h3>
                </div>
                <div class="stat-icon bg-success-light text-success">
                    <i class="bi bi-cart-check"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Stat 3 -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted-custom fw-semibold d-block" style="font-size: 0.85rem;">Pendapatan Hari Ini</span>
                    <h3 class="fw-bold mb-0 mt-1">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="stat-icon bg-primary-light text-primary" style="background-color: #e0e7ff; color: #4f46e5;">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Stat 4 -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted-custom fw-semibold d-block" style="font-size: 0.85rem;">Stok Menipis</span>
                    <h3 class="fw-bold mb-0 mt-1 {{ $lowStock > 0 ? 'text-danger' : '' }}">{{ $lowStock }}</h3>
                </div>
                <div class="stat-icon {{ $lowStock > 0 ? 'bg-danger-light text-danger' : 'bg-secondary-subtle text-secondary' }}">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pintasan Cepat Menu Utama -->
<div class="row mb-5">
    <div class="col-md-6 mb-3">
        <div class="card action-card">
            <div class="card-body">
                <div class="text-primary mb-3" style="font-size: 2rem;"><i class="bi bi-medicine-bottle-fill"></i></div>
                <h4 class="fw-bold text-dark">Manajemen Data Obat</h4>
                <p class="text-muted-custom mt-2">Kelola data obat apotek Anda secara terpusat. Tambah obat baru, pantau harga beli/jual, kelola barcode unik, kategorial, supplier, dan lacak tanggal kadaluarsa.</p>
            </div>
            <div class="card-body pt-0">
                <a href="{{ route('medicines.index') }}" class="btn btn-primary w-100">
                    Buka Data Obat <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card action-card">
            <div class="card-body">
                <div class="text-success mb-3" style="font-size: 2rem;"><i class="bi bi-cash-stack"></i></div>
                <h4 class="fw-bold text-dark">Kasir Penjualan (POS)</h4>
                <p class="text-muted-custom mt-2">Lakukan transaksi penjualan obat dengan pelanggan secara cepat. Dilengkapi pencarian autocomplete, kelola keranjang belanja dinamis, kalkulator uang kembali instan, dan cetak struk.</p>
            </div>
            <div class="card-body pt-0">
                <a href="{{ route('sales.create') }}" class="btn btn-success w-100">
                    Mulai Transaksi Kasir <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Transaksi Terakhir -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pt-4 px-4 pb-1">
        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-primary"></i> 5 Transaksi Terakhir</h5>
    </div>
    <div class="card-body px-4 pb-4">
        @if($recentSales->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Waktu</th>
                        <th>Pelanggan</th>
                        <th>Metode Bayar</th>
                        <th>Total Transaksi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSales as $sale)
                    <tr>
                        <td><span class="fw-bold text-primary">{{ $sale->invoice_number }}</span></td>
                        <td>
                            <div>{{ $sale->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted-custom">{{ $sale->created_at->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            @if($sale->customer)
                                <span class="fw-semibold text-dark">{{ $sale->customer->name }}</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Umum / Non-Member</span>
                            @endif
                        </td>
                        <td>
                            @if($sale->payment_method === 'cash')
                                <span class="badge bg-success-light">Tunai</span>
                            @else
                                <span class="badge bg-primary-light">Non-Tunai</span>
                            @endif
                        </td>
                        <td class="fw-bold text-dark">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                        <td class="text-end">
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-outline-primary px-3">
                                <i class="bi bi-receipt"></i> Struk
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4 text-muted-custom">
            <i class="bi bi-inbox fs-2 d-block mb-1 text-muted"></i>
            Belum ada transaksi penjualan yang tercatat hari ini.
        </div>
        @endif
    </div>
</div>
@endsection