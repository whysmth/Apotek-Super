@extends('layouts.app-simple')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">⚙️ Pengaturan Apotek</h2>
        <p class="text-muted-custom mb-0">Ubah informasi identitas apotek yang tampil di navigasi dan struk belanja</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-sliders"></i> Informasi Profil Apotek</h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Nama Apotek -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Nama Apotek *</label>
                            <input type="text" name="shop_name" class="form-control @error('shop_name') is-invalid @enderror" value="{{ old('shop_name', $shopName) }}" required>
                            @error('shop_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Nama ini akan tampil pada bagian atas navigasi web dan judul struk belanja.</small>
                        </div>
                        
                        <!-- No HP / Telp -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">No. Telepon / HP *</label>
                            <input type="text" name="shop_phone" class="form-control @error('shop_phone') is-invalid @enderror" value="{{ old('shop_phone', $shopPhone) }}" required>
                            @error('shop_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Apotek -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email Apotek</label>
                            <input type="email" name="shop_email" class="form-control @error('shop_email') is-invalid @enderror" value="{{ old('shop_email', $shopEmail) }}">
                            @error('shop_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat Apotek -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-bold">Alamat Lengkap *</label>
                            <textarea name="shop_address" class="form-control @error('shop_address') is-invalid @enderror" rows="3" required>{{ old('shop_address', $shopAddress) }}</textarea>
                            @error('shop_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Alamat fisik lengkap apotek Anda untuk dicetak pada struk belanja.</small>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary shadow-sm px-4">
                                <i class="bi bi-save-fill"></i> Simpan Perubahan
                            </button>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sisi Kanan: Live Preview Struk -->
    <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-header bg-transparent border-0 py-3">
                <h5 class="fw-bold mb-0 text-muted"><i class="bi bi-eye"></i> Tampilan Kop Struk</h5>
            </div>
            <div class="card-body pt-0">
                <div class="bg-white p-4 rounded shadow-sm border border-secondary-subtle text-center" style="font-family: monospace; font-size: 0.85rem;">
                    <div style="font-size: 1.5rem;" class="text-primary"><i class="bi bi-capsule"></i></div>
                    <h5 class="fw-bold text-dark mb-1 text-uppercase" style="letter-spacing: 1px;">{{ $shopName }}</h5>
                    <p class="mb-0 text-muted" style="line-height: 1.3;">
                        {{ $shopAddress }}<br>
                        Telp: {{ $shopPhone }}
                    </p>
                    <hr style="border-top: 1px dashed #cbd5e1; margin: 12px 0;">
                    <div class="text-muted-custom">
                        [ Draf Nota Pembayaran ]
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
