@extends('layouts.app-simple')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">💊 Manajemen Data Obat</h2>
        <p class="text-muted-custom mb-0">Kelola dan pantau persediaan obat-obatan apotek Anda</p>
    </div>
    <a href="{{ route('medicines.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-lg"></i> Tambah Obat Baru
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($medicines->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Info Obat</th>
                        <th>Kategori & Supplier</th>
                        <th>Stok Persediaan</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Tgl Kadaluarsa</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $key => $medicine)
                    <tr>
                        <td class="ps-4 text-muted-custom fw-semibold">{{ $key + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $medicine->name }}</div>
                            <small class="text-muted-custom d-flex align-items-center gap-1 mt-0.5">
                                <i class="bi bi-qr-code"></i> {{ $medicine->barcode }}
                                @if($medicine->dosage_form || $medicine->strength)
                                    <span>•</span> <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.75rem;">{{ $medicine->dosage_form }} ({{ $medicine->strength }})</span>
                                @endif
                            </small>
                        </td>
                        <td>
                            <div class="mb-1">
                                <span class="badge bg-primary-light" style="font-size: 0.8rem;">{{ $medicine->category->name ?? 'Kategori' }}</span>
                            </div>
                            <div>
                                <small class="text-muted-custom"><i class="bi bi-truck"></i> {{ $medicine->supplier->name ?? 'Supplier' }}</small>
                            </div>
                        </td>
                        <td>
                            @if($medicine->stock <= $medicine->min_stock)
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-5 fw-bold text-danger">{{ $medicine->stock }}</span>
                                    <span class="badge bg-danger-light"><i class="bi bi-exclamation-circle-fill"></i> Stok Kritis</span>
                                </div>
                                <small class="text-muted-custom">Minimal stok: {{ $medicine->min_stock }}</small>
                            @else
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-5 fw-bold text-dark">{{ $medicine->stock }}</span>
                                    <span class="badge bg-success-light"><i class="bi bi-check-circle-fill"></i> Aman</span>
                                </div>
                            @endif
                        </td>
                        <td class="text-muted-custom">
                            Rp {{ number_format($medicine->purchase_price, 0, ',', '.') }}
                        </td>
                        <td class="fw-bold text-primary">
                            Rp {{ number_format($medicine->selling_price, 0, ',', '.') }}
                        </td>
                        <td>
                            @php
                                $expiryDate = $medicine->expiry_date;
                                $isExpired = $expiryDate->isPast();
                                $isSoon = !$isExpired && $expiryDate->diffInDays(now()) <= 90;
                            @endphp
                            
                            @if($isExpired)
                                <div class="text-danger fw-bold"><i class="bi bi-calendar-x-fill"></i> Kadaluarsa</div>
                                <small class="text-danger fw-semibold">{{ $expiryDate->format('d M Y') }}</small>
                            @elseif($isSoon)
                                <div class="text-warning fw-bold"><i class="bi bi-calendar-event-fill"></i> Hampir Kadaluarsa</div>
                                <small class="text-warning fw-semibold">{{ $expiryDate->format('d M Y') }}</small>
                            @else
                                <div class="text-dark fw-semibold">{{ $expiryDate->format('d M Y') }}</div>
                                <small class="text-muted-custom">{{ $expiryDate->diffForHumans() }}</small>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat \'{{ $medicine->name }}\' ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-5 text-center">
            <div class="display-1 text-muted"><i class="bi bi-capsule-pill"></i></div>
            <h5 class="mt-3 fw-bold text-muted">Belum Ada Data Obat</h5>
            <p class="text-muted-custom max-w-md mx-auto">Silakan tambahkan data obat baru terlebih dahulu agar dapat digunakan di kasir penjualan.</p>
            <a href="{{ route('medicines.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus-lg"></i> Tambah Obat Baru
            </a>
        </div>
        @endif
    </div>
</div>
@endsection