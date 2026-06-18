@extends('layouts.app-simple')

@section('styles')
<style>
    /* Styling khusus struk */
    .receipt-card {
        max-width: 450px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-md);
        padding: 24px;
        position: relative;
    }
    
    .receipt-header {
        text-align: center;
        margin-bottom: 20px;
    }
    
    .receipt-logo {
        font-size: 2.2rem;
        color: var(--primary-color);
        margin-bottom: 4px;
    }

    .receipt-divider {
        border-top: 1px dashed var(--border-color);
        margin: 16px 0;
    }
    
    .receipt-item-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .receipt-summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
        font-size: 0.95rem;
    }

    .receipt-summary-row.total {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-top: 10px;
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }
        .receipt-card, .receipt-card * {
            visibility: visible;
        }
        .receipt-card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
            padding: 0;
            margin: 0;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        
        <!-- Tombol Aksi (Hanya muncul di web, tidak di print) -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Riwayat Penjualan
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('sales.create') }}" class="btn btn-success">
                    <i class="bi bi-cart-plus"></i> Kasir Baru
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer-fill"></i> Cetak Struk
                </button>
            </div>
        </div>

        <!-- Struk Penjualan Card -->
        <div class="receipt-card">
            
            <!-- Header Apotek -->
            <div class="receipt-header">
                <div class="receipt-logo"><i class="bi bi-capsule"></i></div>
                <h4 class="fw-bold mb-1 text-primary">{{ \App\Models\Setting::get('shop_name', 'Apotek Super') }}</h4>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">
                    {{ \App\Models\Setting::get('shop_address', 'Jl. Laragon No. 1, Kota Laragon') }}<br>
                    Telp: {{ \App\Models\Setting::get('shop_phone', '0812-3456-7890') }}
                </p>
            </div>
            
            <!-- Detail Struk -->
            <div style="font-size: 0.85rem;" class="text-muted-custom">
                <div class="d-flex justify-content-between mb-1">
                    <span>No. Invoice:</span>
                    <span class="fw-bold text-dark">{{ $sale->invoice_number }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Waktu:</span>
                    <span class="text-dark">{{ $sale->created_at->format('d/m/Y H:i') }} WIB</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Kasir:</span>
                    <span class="text-dark">{{ $sale->user->name ?? 'Admin Apotek' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Pelanggan:</span>
                    <span class="text-dark">{{ $sale->customer->name ?? 'Umum / Non-Member' }}</span>
                </div>
            </div>
            
            <div class="receipt-divider"></div>
            
            <!-- Daftar Item Belanja -->
            <div>
                <div class="fw-bold mb-2 text-dark" style="font-size: 0.9rem;">Daftar Item:</div>
                @foreach($sale->items as $item)
                <div class="receipt-item-row">
                    <div>
                        <div class="fw-semibold text-dark">{{ $item->medicine->name }}</div>
                        <small class="text-muted-custom">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                    </div>
                    <div class="fw-bold text-dark">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="receipt-divider"></div>
            
            <!-- Ringkasan Transaksi -->
            <div>
                <div class="receipt-summary-row">
                    <span class="text-muted-custom">Subtotal:</span>
                    <span class="fw-semibold text-dark">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="receipt-summary-row">
                    <span class="text-muted-custom">Metode Bayar:</span>
                    <span class="fw-semibold text-dark">
                        {{ $sale->payment_method === 'cash' ? 'Tunai' : 'Non-Tunai' }}
                    </span>
                </div>
                <div class="receipt-summary-row">
                    <span class="text-muted-custom">Uang Bayar:</span>
                    <span class="fw-semibold text-dark">Rp {{ number_format($sale->payment_amount, 0, ',', '.') }}</span>
                </div>
                <div class="receipt-summary-row">
                    <span class="text-muted-custom">Uang Kembali:</span>
                    <span class="fw-semibold text-success">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
                </div>
                
                <div class="receipt-summary-row total">
                    <span>TOTAL BAYAR:</span>
                    <span>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <div class="receipt-divider"></div>
            
            <!-- Footer Struk -->
            <div class="text-center text-muted-custom" style="font-size: 0.85rem; line-height: 1.4;">
                <p class="fw-semibold mb-1 text-dark">Terima Kasih Atas Kunjungan Anda</p>
                <p class="mb-0">Semoga Lekas Sembuh!</p>
            </div>
            
        </div>
        
    </div>
</div>
@endsection
