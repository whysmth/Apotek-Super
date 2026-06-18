@extends('layouts.app-simple')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">💊 Tambah Obat Baru</h2>
        <p class="text-muted-custom mb-0">Daftarkan obat baru ke dalam database persediaan apotek</p>
    </div>
    <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('medicines.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- Barcode -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Barcode *</label>
                    <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode') }}" placeholder="Contoh: 8990001112221" required>
                    @error('barcode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Nama Obat -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Obat *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Paracetamol 500mg" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kategori -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kategori *</label>
                    <div class="input-group">
                        <select name="category_id" id="categoryIdSelect" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                    </div>
                    @error('category_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Supplier -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Supplier *</label>
                    <div class="input-group">
                        <select name="supplier_id" id="supplierIdSelect" class="form-select @error('supplier_id') is-invalid @enderror" required>
                            <option value="">Pilih Supplier</option>
                            @foreach(\App\Models\Supplier::all() as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                    </div>
                    @error('supplier_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Bentuk Obat -->
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Bentuk Obat *</label>
                    <select name="dosage_form" class="form-select @error('dosage_form') is-invalid @enderror" required>
                        <option value="">Pilih</option>
                        <option value="Tablet" {{ old('dosage_form') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                        <option value="Kapsul" {{ old('dosage_form') == 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                        <option value="Sirup" {{ old('dosage_form') == 'Sirup' ? 'selected' : '' }}>Sirup</option>
                        <option value="Salep" {{ old('dosage_form') == 'Salep' ? 'selected' : '' }}>Salep</option>
                    </select>
                    @error('dosage_form')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kekuatan -->
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Kekuatan *</label>
                    <input type="text" name="strength" class="form-control @error('strength') is-invalid @enderror" value="{{ old('strength') }}" placeholder="Contoh: 500mg atau 15ml" required>
                    @error('strength')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Stok -->
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Stok Awal *</label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Minimal Stok -->
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Minimal Stok *</label>
                    <input type="number" name="min_stock" class="form-control @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', 10) }}" min="0" required>
                    @error('min_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Harga Beli -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Harga Beli (Rp) *</label>
                    <input type="number" name="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price') }}" min="0" required>
                    @error('purchase_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Harga Jual -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Harga Jual (Rp) *</label>
                    <input type="number" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{ old('selling_price') }}" min="0" required>
                    @error('selling_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Tanggal Kadaluarsa -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Tanggal Kadaluarsa *</label>
                    <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date') }}" required>
                    @error('expiry_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Keterangan obat (dosis, efek samping, dll.)...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Tombol Aksi -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary shadow-sm px-4">
                        <i class="bi bi-save-fill"></i> Simpan Obat
                    </button>
                    <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Kategori Baru -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-lg);">
            <div class="modal-header border-0 py-3 px-4">
                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-tag-fill"></i> Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ajaxCategoryForm">
                <div class="modal-body py-0 px-4">
                    <div class="alert alert-danger d-none" id="categoryAlert"></div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kategori *</label>
                        <input type="text" id="catNameInput" class="form-control" placeholder="Contoh: Obat Batuk & Flu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea id="catDescInput" class="form-control" rows="2" placeholder="Keterangan singkat kategori..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="saveCategoryBtn">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Supplier Baru -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-lg);">
            <div class="modal-header border-0 py-3 px-4">
                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-truck-flatbed"></i> Tambah Supplier Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ajaxSupplierForm">
                <div class="modal-body py-0 px-4">
                    <div class="alert alert-danger d-none" id="supplierAlert"></div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Supplier *</label>
                        <input type="text" id="supNameInput" class="form-control" placeholder="Contoh: PT Medika Jaya Utama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">No. Telepon / HP *</label>
                        <input type="text" id="supPhoneInput" class="form-control" placeholder="Contoh: 08123456789" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" id="supEmailInput" class="form-control" placeholder="Contoh: sales@medikajaya.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Lengkap *</label>
                        <textarea id="supAddressInput" class="form-control" rows="2" placeholder="Alamat kantor / gudang supplier..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="saveSupplierBtn">Simpan Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // AJAX Category Form Submission
    document.getElementById('ajaxCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const saveBtn = document.getElementById('saveCategoryBtn');
        const alertBox = document.getElementById('categoryAlert');
        
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
        alertBox.classList.add('d-none');
        
        const data = {
            name: document.getElementById('catNameInput').value,
            description: document.getElementById('catDescInput').value,
            _token: '{{ csrf_token() }}'
        };
        
        fetch('{{ route("categories.storeAjax") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(res => {
            saveBtn.disabled = false;
            saveBtn.innerText = 'Simpan Kategori';
            
            if (res.success) {
                // Tambahkan opsi baru ke select dropdown
                const selectEl = document.getElementById('categoryIdSelect');
                const newOption = new Option(res.category.name, res.category.id, true, true);
                selectEl.add(newOption);
                
                // Reset form & tutup modal
                document.getElementById('ajaxCategoryForm').reset();
                const modalEl = document.getElementById('addCategoryModal');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (!modal) {
                    modal = new bootstrap.Modal(modalEl);
                }
                modal.hide();
            } else {
                alertBox.innerText = res.message || 'Terjadi kesalahan saat menyimpan.';
                alertBox.classList.remove('d-none');
            }
        })
        .catch(err => {
            saveBtn.disabled = false;
            saveBtn.innerText = 'Simpan Kategori';
            alertBox.innerText = 'Terjadi kesalahan sistem, silakan coba lagi.';
            alertBox.classList.remove('d-none');
            console.error(err);
        });
    });

    // AJAX Supplier Form Submission
    document.getElementById('ajaxSupplierForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const saveBtn = document.getElementById('saveSupplierBtn');
        const alertBox = document.getElementById('supplierAlert');
        
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
        alertBox.classList.add('d-none');
        
        const data = {
            name: document.getElementById('supNameInput').value,
            phone: document.getElementById('supPhoneInput').value,
            email: document.getElementById('supEmailInput').value,
            address: document.getElementById('supAddressInput').value,
            _token: '{{ csrf_token() }}'
        };
        
        fetch('{{ route("suppliers.storeAjax") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(res => {
            saveBtn.disabled = false;
            saveBtn.innerText = 'Simpan Supplier';
            
            if (res.success) {
                // Tambahkan opsi baru ke select dropdown
                const selectEl = document.getElementById('supplierIdSelect');
                const newOption = new Option(res.supplier.name, res.supplier.id, true, true);
                selectEl.add(newOption);
                
                // Reset form & tutup modal
                document.getElementById('ajaxSupplierForm').reset();
                const modalEl = document.getElementById('addSupplierModal');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (!modal) {
                    modal = new bootstrap.Modal(modalEl);
                }
                modal.hide();
            } else {
                alertBox.innerText = res.message || 'Terjadi kesalahan saat menyimpan.';
                alertBox.classList.remove('d-none');
            }
        })
        .catch(err => {
            saveBtn.disabled = false;
            saveBtn.innerText = 'Simpan Supplier';
            alertBox.innerText = 'Terjadi kesalahan sistem, silakan coba lagi.';
            alertBox.classList.remove('d-none');
            console.error(err);
        });
    });
</script>
@endsection