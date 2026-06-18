@extends('layouts.app-simple')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">💰 Riwayat Transaksi Penjualan</h2>
        <p class="text-muted-custom mb-0">Kelola dan lihat daftar transaksi apotek Anda</p>
    </div>
    <a href="{{ route('sales.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-cart-plus-fill"></i> Kasir Baru
    </a>
</div>

<!-- Dasbor Statistik Mini -->
<div class="row g-4 mb-4">
    <!-- Total Omset -->
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold d-block mb-1" style="font-size: 0.9rem;">Total Pendapatan (Omset)</span>
                        <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-primary-light p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-wallet2 text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Transaksi -->
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold d-block mb-1" style="font-size: 0.9rem;">Total Transaksi</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalTransactions, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-success-light p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-cart-check-fill text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rata-rata AOV -->
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold d-block mb-1" style="font-size: 0.9rem;">Rata-rata Transaksi (AOV)</span>
                        <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-warning-light p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-graph-up text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Panel Pencarian & Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('sales.index') }}" method="GET" id="filterForm">
            <div class="row g-3 align-items-end">
                <!-- Search Input -->
                <div class="col-12 col-md-4">
                    <label for="search" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">Cari Transaksi</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" id="search" class="form-control border-start-0 ps-0" placeholder="No. Invoice, Kasir, atau Pelanggan..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="col-12 col-md-2">
                    <label for="payment_method" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">Metode Bayar</label>
                    <select name="payment_method" id="payment_method" class="form-select">
                        <option value="">Semua</option>
                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="cashless" {{ request('payment_method') === 'cashless' ? 'selected' : '' }}>Non-Tunai</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div class="col-12 col-md-3">
                    <label for="date_range" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">Rentang Tanggal</label>
                    <select name="date_range" id="date_range" class="form-select">
                        <option value="">Semua Waktu</option>
                        <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="yesterday" {{ request('date_range') === 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                        <option value="last_7_days" {{ request('date_range') === 'last_7_days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="month" {{ request('date_range') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="custom" {{ request('date_range') === 'custom' ? 'selected' : '' }}>Kustom Tanggal...</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-filter"></i> Saring</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                    <button type="button" id="btnExport" class="btn btn-success" title="Ekspor ke CSV (Excel)"><i class="bi bi-file-earmark-spreadsheet-fill"></i> Ekspor</button>
                </div>
            </div>

            <!-- Custom Date Inputs (Show/Hide via JS) -->
            <div class="row mt-3 g-3 d-none" id="customDateContainer">
                <div class="col-12 col-md-6">
                    <label for="start_date" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-12 col-md-6">
                    <label for="end_date" class="form-label fw-bold text-muted" style="font-size: 0.85rem;">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Riwayat Transaksi -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($sales->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th>No. Invoice</th>
                        <th>Tanggal Transaksi</th>
                        <th>Kasir</th>
                        <th>Pelanggan</th>
                        <th>Metode</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th class="pe-4 text-end" style="min-width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $key => $sale)
                    <tr>
                        <td class="ps-4 text-muted-custom fw-semibold">{{ $key + 1 }}</td>
                        <td>
                            <span class="fw-bold text-primary">{{ $sale->invoice_number }}</span>
                        </td>
                        <td>
                            <div>{{ $sale->created_at->format('d M Y') }}</div>
                            <small class="text-muted-custom">{{ $sale->created_at->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary-light rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.8rem; font-weight: 700;">
                                    {{ strtoupper(substr($sale->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <span class="fw-semibold text-muted-custom" style="font-size: 0.9rem;">
                                    {{ $sale->user->name ?? 'Admin Apotek' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($sale->customer)
                                <div class="fw-semibold text-dark">{{ $sale->customer->name }}</div>
                                <small class="text-muted-custom">{{ $sale->customer->member_number }}</small>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Umum / Non-Member</span>
                            @endif
                        </td>
                        <td>
                            @if($sale->payment_method === 'cash')
                                <span class="badge bg-success-light"><i class="bi bi-cash"></i> Tunai</span>
                            @else
                                <span class="badge bg-primary-light"><i class="bi bi-credit-card"></i> Non-Tunai</span>
                            @endif
                        </td>
                        <td class="fw-bold text-dark">
                            Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($sale->status === 'cancelled')
                                <span class="badge bg-danger-light"><i class="bi bi-x-circle-fill"></i> Dibatalkan</span>
                            @else
                                <span class="badge bg-success-light"><i class="bi bi-check-circle-fill"></i> Selesai</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary border-2 px-3 btn-detail" data-id="{{ $sale->id }}">
                                    <i class="bi bi-receipt"></i> Detail Struk
                                </button>
                                
                                @if($sale->status !== 'cancelled')
                                <form action="{{ route('sales.void', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok obat akan dikembalikan otomatis ke persediaan.')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-2 px-3">
                                        <i class="bi bi-x-octagon"></i> Void
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-5 text-center">
            <div class="display-1 text-muted"><i class="bi bi-inbox"></i></div>
            <h5 class="mt-3 fw-bold text-muted">Belum Ada Transaksi Penjualan</h5>
            <p class="text-muted-custom max-w-md mx-auto">Riwayat penjualan Anda akan muncul di sini setelah transaksi kasir berhasil disimpan atau disaring.</p>
            <a href="{{ route('sales.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-cart-plus-fill"></i> Mulai Transaksi Pertama
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Modal Detail Transaksi -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-lg);">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="detailModalLabel">🧾 Rincian Struk Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="text-center mb-4 py-2 bg-light rounded" style="border-radius: var(--radius-md);">
                    <small class="text-muted-custom d-block mb-1" style="font-size: 0.8rem;">NOMOR INVOICE</small>
                    <h4 class="fw-bold text-primary mb-1" id="modalInvoice">-</h4>
                    <span class="badge" id="modalStatusBadge">-</span>
                </div>

                <div class="row g-3 mb-4 text-start">
                    <div class="col-6 col-sm-3">
                        <small class="text-muted-custom d-block">Tanggal</small>
                        <span class="fw-semibold text-dark" id="modalDate">-</span>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted-custom d-block">Metode Pembayaran</small>
                        <span class="fw-semibold text-dark" id="modalPaymentMethod">-</span>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted-custom d-block">Kasir</small>
                        <span class="fw-semibold text-dark" id="modalCashier">-</span>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted-custom d-block">Pelanggan</small>
                        <span class="fw-semibold text-dark" id="modalCustomer">-</span>
                    </div>
                </div>

                <h6 class="fw-bold mb-3"><i class="bi bi-capsule text-primary"></i> Daftar Item Obat</h6>
                <div class="table-responsive mb-4">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Obat</th>
                                <th class="text-center" style="width: 150px;">Harga Satuan</th>
                                <th class="text-center" style="width: 100px;">Jumlah</th>
                                <th class="text-end" style="width: 150px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalItemsTable">
                            <!-- Items loaded dynamically -->
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-12 col-md-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted-custom">Total Pembelian</span>
                            <span class="fw-bold text-dark fs-5" id="modalTotal">-</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted-custom">Bayar</span>
                            <span class="fw-semibold text-dark" id="modalPayment">-</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold text-dark">Kembalian</span>
                            <span class="fw-bold text-primary fs-5" id="modalChange">-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="modalPrintLink" class="btn btn-primary px-4"><i class="bi bi-printer"></i> Halaman Struk</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Logika untuk toggle filter tanggal kustom
    const dateRangeSelect = document.getElementById('date_range');
    const customDateContainer = document.getElementById('customDateContainer');
    
    function toggleCustomDate() {
        if (dateRangeSelect.value === 'custom') {
            customDateContainer.classList.remove('d-none');
        } else {
            customDateContainer.classList.add('d-none');
            // Reset input tanggal jika disembunyikan
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
        }
    }
    
    dateRangeSelect.addEventListener('change', toggleCustomDate);
    // Jalankan sekali saat load untuk menjaga state filter
    toggleCustomDate();

    // 2. Logika ekspor CSV
    document.getElementById('btnExport').addEventListener('click', function() {
        const form = document.getElementById('filterForm');
        // Gunakan parameter url saat ini agar filter tersinkronisasi
        const params = new URLSearchParams(new FormData(form)).toString();
        window.location.href = "{{ route('sales.export') }}?" + params;
    });

    // 3. Logika modal detail struk asinkronus (Quick View)
    const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
    const btnDetails = document.querySelectorAll('.btn-detail');

    btnDetails.forEach(button => {
        button.addEventListener('click', function() {
            const saleId = this.getAttribute('data-id');
            
            // Set loading state atau reset isi modal terlebih dahulu
            document.getElementById('modalInvoice').innerText = 'Loading...';
            document.getElementById('modalItemsTable').innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat data...</td></tr>';
            
            detailModal.show();

            // Fetch AJAX data transaksi
            fetch(`/sales/${saleId}/json`)
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        const sale = res.data;
                        
                        // Isi data utama modal
                        document.getElementById('modalInvoice').innerText = sale.invoice_number;
                        
                        // Status badge
                        const statusBadge = document.getElementById('modalStatusBadge');
                        if (sale.status === 'cancelled') {
                            statusBadge.className = 'badge bg-danger-light';
                            statusBadge.innerHTML = '<i class="bi bi-x-circle-fill"></i> Dibatalkan';
                        } else {
                            statusBadge.className = 'badge bg-success-light';
                            statusBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Selesai';
                        }

                        // Tanggal, Kasir, Pelanggan, dll
                        const createdDate = new Date(sale.created_at);
                        const formattedDate = createdDate.toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        }) + ' ' + String(createdDate.getHours()).padStart(2, '0') + ':' + String(createdDate.getMinutes()).padStart(2, '0') + ' WIB';
                        
                        document.getElementById('modalDate').innerText = formattedDate;
                        document.getElementById('modalPaymentMethod').innerText = sale.payment_method === 'cash' ? 'Tunai' : 'Non-Tunai';
                        document.getElementById('modalCashier').innerText = sale.user ? sale.user.name : 'Admin Apotek';
                        document.getElementById('modalCustomer').innerText = sale.customer ? sale.customer.name : 'Umum / Non-Member';
                        
                        // Format mata uang rupiah
                        const formatter = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        });

                        document.getElementById('modalTotal').innerText = formatter.format(sale.total_amount);
                        document.getElementById('modalPayment').innerText = formatter.format(sale.payment_amount);
                        document.getElementById('modalChange').innerText = formatter.format(sale.change_amount);
                        
                        // Set link print struk
                        document.getElementById('modalPrintLink').setAttribute('href', `/sales/${sale.id}`);

                        // Load data items
                        let itemsHtml = '';
                        sale.items.forEach(item => {
                            const medicineName = item.medicine ? item.medicine.name : 'Obat Terhapus';
                            itemsHtml += `
                                <tr>
                                    <td>
                                        <span class="fw-bold">${medicineName}</span>
                                    </td>
                                    <td class="text-center">${formatter.format(item.price)}</td>
                                    <td class="text-center fw-semibold">${item.quantity}</td>
                                    <td class="text-end fw-bold text-dark">${formatter.format(item.subtotal)}</td>
                                </tr>
                            `;
                        });
                        document.getElementById('modalItemsTable').innerHTML = itemsHtml;
                    } else {
                        alert('Gagal mengambil data detail penjualan.');
                        detailModal.hide();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan jaringan atau data tidak dapat ditemukan.');
                    detailModal.hide();
                });
        });
    });
});
</script>
@endsection
