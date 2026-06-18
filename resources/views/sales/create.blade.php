@extends('layouts.app-simple')

@section('styles')
<style>
    .scrollable-card {
        max-height: 520px;
        overflow-y: auto;
    }
    .medicine-item {
        cursor: pointer;
        transition: background-color 0.15s ease;
        border-bottom: 1px solid var(--border-color);
        padding: 12px 16px;
    }
    .medicine-item:hover {
        background-color: var(--primary-light);
    }
    .cart-qty-input {
        width: 60px;
        text-align: center;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 4px;
        font-weight: 700;
    }
    .cart-qty-btn {
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">🛒 Kasir Penjualan</h2>
        <p class="text-muted-custom mb-0">Lakukan transaksi penjualan obat secara cepat dan dinamis</p>
    </div>
    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
    </a>
</div>

<form action="{{ route('sales.store') }}" method="POST" id="transactionForm">
    @csrf
    
    <div class="row">
        <!-- Kiri: Keranjang Belanja -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0 text-primary d-flex align-items-center gap-2">
                        <i class="bi bi-cart3"></i> Detail Keranjang
                    </h5>
                </div>
                
                <div class="card-body p-0 d-flex flex-column justify-content-between" style="min-height: 400px;">
                    <!-- Tabel Keranjang -->
                    <div class="table-responsive flex-grow-1" style="max-height: 320px; overflow-y: auto;">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Nama Obat</th>
                                    <th>Harga Jual</th>
                                    <th>Kuantitas</th>
                                    <th>Subtotal</th>
                                    <th class="pe-4 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted-custom">
                                        <i class="bi bi-cart-x fs-1 d-block mb-2 text-muted"></i>
                                        Keranjang masih kosong. Pilih obat di sebelah kanan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Ringkasan Pembayaran -->
                    <div class="bg-light p-4 border-top">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-bold">Pelanggan</label>
                                <select name="customer_id" class="form-select">
                                    <option value="">Umum / Non-Member</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->member_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 mt-3 mt-sm-0">
                                <label class="form-label fw-bold">Metode Pembayaran</label>
                                <select name="payment_method" id="paymentMethod" class="form-select">
                                    <option value="cash">Tunai</option>
                                    <option value="cashless">Non-Tunai (QRIS/Debit)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Perhitungan Total -->
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary-subtle">
                            <span class="fs-5 fw-bold">Total Transaksi</span>
                            <span class="fs-4 fw-extrabold text-primary" id="totalAmountText">Rp 0</span>
                        </div>

                        <div class="row mt-3 align-items-center">
                            <div class="col-sm-6">
                                <label class="form-label fw-bold">Uang Dibayarkan (Rp)</label>
                                <input type="number" name="payment_amount" id="paymentAmountInput" class="form-control form-control-lg fw-bold text-success" placeholder="0" min="0" required>
                            </div>
                            <div class="col-sm-6 mt-3 mt-sm-0 text-sm-end">
                                <span class="d-block fw-bold text-muted">Uang Kembalian</span>
                                <span class="fs-3 fw-bold text-success" id="changeAmountText">Rp 0</span>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm" id="submitBtn" disabled>
                                <i class="bi bi-check-circle-fill"></i> Simpan Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanan: Pilih Obat -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0 text-primary">
                        <i class="bi bi-search"></i> Cari & Pilih Obat
                    </h5>
                    <div class="input-group mt-3">
                        <span class="input-group-text bg-white border-end-0 text-muted-custom">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="searchMedicine" class="form-control border-start-0 ps-0" placeholder="Ketik nama obat atau barcode...">
                    </div>
                </div>
                
                <div class="card-body p-0 d-flex flex-column">
                    <div class="scrollable-card flex-grow-1" id="medicineListContainer">
                        @foreach($medicines as $medicine)
                            <div class="medicine-item d-flex justify-content-between align-items-center" 
                                 data-id="{{ $medicine->id }}" 
                                 data-name="{{ $medicine->name }}" 
                                 data-price="{{ $medicine->selling_price }}" 
                                 data-stock="{{ $medicine->stock }}"
                                 data-barcode="{{ $medicine->barcode }}"
                                 onclick="addToCart({{ $medicine->id }}, '{{ $medicine->name }}', {{ $medicine->selling_price }}, {{ $medicine->stock }})">
                                <div>
                                    <div class="fw-bold">{{ $medicine->name }}</div>
                                    <small class="text-muted-custom d-block">
                                        <i class="bi bi-qr-code"></i> {{ $medicine->barcode }} | Stok: <span class="fw-semibold text-dark">{{ $medicine->stock }}</span>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">Rp {{ number_format($medicine->selling_price, 0, ',', '.') }}</div>
                                    <span class="badge bg-primary-light rounded-pill"><i class="bi bi-plus-lg"></i> Tambah</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    let cart = [];

    // Search function
    document.getElementById('searchMedicine').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.medicine-item');
        
        items.forEach(item => {
            const name = item.getAttribute('data-name').toLowerCase();
            const barcode = item.getAttribute('data-barcode').toLowerCase();
            
            if (name.includes(query) || barcode.includes(query)) {
                item.style.setProperty('display', 'flex', 'important');
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });
    });

    // Add item to cart
    function addToCart(id, name, price, stock) {
        // Cari apakah item sudah ada di keranjang
        const existingItem = cart.find(item => item.id === id);
        
        if (existingItem) {
            if (existingItem.quantity < stock) {
                existingItem.quantity++;
            } else {
                alert(`Maaf, stok obat '${name}' tidak mencukupi untuk ditambahkan lagi.`);
            }
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                stock: stock,
                quantity: 1
            });
        }
        
        renderCart();
    }

    // Change quantity in cart
    function updateQuantity(id, newQty) {
        const item = cart.find(item => item.id === id);
        if (!item) return;
        
        newQty = parseInt(newQty);
        
        if (isNaN(newQty) || newQty < 1) {
            newQty = 1;
        }
        
        if (newQty > item.stock) {
            alert(`Stok maksimal untuk '${item.name}' adalah ${item.stock}.`);
            newQty = item.stock;
        }
        
        item.quantity = newQty;
        renderCart();
    }

    // Remove item from cart
    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        renderCart();
    }

    // Render cart items to table
    function renderCart() {
        const tbody = document.getElementById('cartTableBody');
        const submitBtn = document.getElementById('submitBtn');
        const paymentInput = document.getElementById('paymentAmountInput');
        
        if (cart.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted-custom">
                        <i class="bi bi-cart-x fs-1 d-block mb-2 text-muted"></i>
                        Keranjang masih kosong. Pilih obat di sebelah kanan.
                    </td>
                </tr>
            `;
            submitBtn.disabled = true;
            document.getElementById('totalAmountText').innerText = "Rp 0";
            paymentInput.value = "";
            paymentInput.readOnly = true;
            calculateChange(0);
            return;
        }

        paymentInput.readOnly = false;
        submitBtn.disabled = false;
        
        let html = '';
        let total = 0;
        
        cart.forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            
            html += `
                <tr data-id="${item.id}">
                    <td class="ps-4">
                        <div class="fw-bold">${item.name}</div>
                        <!-- Hidden inputs for form submit -->
                        <input type="hidden" name="items[${index}][medicine_id]" value="${item.id}">
                        <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                        <small class="text-muted">Stok tersedia: ${item.stock}</small>
                    </td>
                    <td>Rp ${formatNumber(item.price)}</td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-outline-secondary btn-sm cart-qty-btn" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="cart-qty-input" value="${item.quantity}" min="1" max="${item.stock}" onchange="updateQuantity(${item.id}, this.value)">
                            <button type="button" class="btn btn-outline-secondary btn-sm cart-qty-btn" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="fw-bold text-dark">Rp ${formatNumber(subtotal)}</td>
                    <td class="pe-4 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeFromCart(${item.id})">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        document.getElementById('totalAmountText').innerText = `Rp ${formatNumber(total)}`;
        paymentInput.min = total;
        
        // Auto fill payment amount on cashless
        const paymentMethod = document.getElementById('paymentMethod').value;
        if (paymentMethod === 'cashless') {
            paymentInput.value = total;
            paymentInput.readOnly = true;
        }

        calculateChange(total);
    }

    // Payment Method change trigger
    document.getElementById('paymentMethod').addEventListener('change', function(e) {
        const total = getCartTotal();
        const paymentInput = document.getElementById('paymentAmountInput');
        
        if (e.target.value === 'cashless') {
            paymentInput.value = total;
            paymentInput.readOnly = true;
        } else {
            paymentInput.value = "";
            paymentInput.readOnly = false;
        }
        calculateChange(total);
    });

    // Payment amount change trigger
    document.getElementById('paymentAmountInput').addEventListener('input', function() {
        calculateChange(getCartTotal());
    });

    // Helper: calculate change
    function calculateChange(total) {
        const payment = parseFloat(document.getElementById('paymentAmountInput').value) || 0;
        const changeText = document.getElementById('changeAmountText');
        const submitBtn = document.getElementById('submitBtn');
        
        if (payment >= total && total > 0) {
            const change = payment - total;
            changeText.innerText = `Rp ${formatNumber(change)}`;
            submitBtn.disabled = false;
        } else {
            changeText.innerText = `Rp 0`;
            if (total > 0) {
                submitBtn.disabled = true;
            }
        }
    }

    // Helper: Get current cart total
    function getCartTotal() {
        return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    }

    // Helper: Format number to Indonesian rupiah style
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
</script>
@endsection
