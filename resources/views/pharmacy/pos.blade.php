<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Clinical Pro || Pharmacy POS</title>

<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

<style>
    /* Full Screen POS Layout */
    body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; overflow: hidden; }
    
    .pos-topbar {
        position: fixed; top: 0; left: 0; width: 100%; height: 60px;
        background: #007bff; color: #fff; z-index: 1000;
        display: flex; align-items: center; justify-content: space-between; padding: 0 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .brand-logo { font-size: 20px; font-weight: 700; letter-spacing: 1px; }
    .user-profile { font-size: 14px; }
    
    /* Layout */
    .pos-content { display: flex; width: 100%; margin-top: 60px; height: calc(100vh - 60px); }
    .left-panel { flex: 65%; padding: 20px; display: flex; flex-direction: column; overflow: hidden; }
    .right-panel { flex: 35%; background: #fff; border-left: 1px solid #e0e0e0; display: flex; flex-direction: column; box-shadow: -5px 0 20px rgba(0,0,0,0.05); }
    
    /* RESPONSIVE FIXES */
    @media (max-width: 992px) {
        body { overflow: auto; } /* Allow scroll on mobile */
        .pos-content { flex-direction: column; height: auto; }
        .left-panel { flex: none; width: 100%; height: auto; overflow: visible; padding-bottom: 20px; }
        .right-panel { flex: none; width: 100%; height: auto; border-left: none; border-top: 2px solid #007bff; }
        .product-grid { overflow-y: visible; padding-bottom: 0; }
        .cart-body { max-height: 400px; overflow-y: auto; }
    }

    .search-row { display: flex; gap: 15px; margin-bottom: 20px; }
    .search-card { background: #fff; padding: 15px; border-radius: 10px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; align-items: center; cursor: text; transition: 0.2s; }
    .search-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .search-card i { font-size: 20px; color: #999; margin-right: 10px; }
    .search-input { border: none; outline: none; width: 100%; font-size: 15px; color: #333; }
    
    /* Product Grid */
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px; overflow-y: auto; padding-bottom: 50px; }
    .drug-card { 
        background: #fff; border-radius: 10px; padding: 15px; 
        cursor: pointer; transition: transform 0.1s, box-shadow 0.1s; 
        border: 1px solid #eee; display: flex; flex-direction: column; justify-content: space-between; height: 140px; position: relative;
    }
    .drug-card:active { transform: scale(0.98); }
    .drug-card:hover { border-color: #007bff; box-shadow: 0 5px 15px rgba(0,123,255,0.1); }
    .drug-name { font-weight: 600; font-size: 14px; color: #333; line-height: 1.3; margin-bottom: 5px; margin-top: 15px; }
    .drug-stock { font-size: 11px; color: #888; }
    .drug-price { font-weight: 700; color: #007bff; font-size: 16px; margin-top: auto; }
    
    /* Stock Badges */
    .badge-stock { position: absolute; top: 10px; right: 10px; font-size: 11px; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
    .badge-stock-high { background: #e8f5e9; color: #2e7d32; } 
    .badge-stock-low { background: #fff3e0; color: #ef6c00; }
    .badge-stock-out { background: #ffebee; color: #c62828; }
    
    /* Cart */
    .cart-header { padding: 20px; border-bottom: 1px solid #f0f0f0; background: #fafafa; }
    .patient-selector { 
        background: #e3f2fd; color: #007bff; padding: 10px 15px; border-radius: 8px; 
        cursor: pointer; display: flex; align-items: center; justify-content: space-between; font-weight: 600;
    }
    .cart-body { flex-grow: 1; overflow-y: auto; padding: 0; }
    .cart-item { padding: 15px; border-bottom: 1px solid #f5f5f5; display: flex; align-items: center; justify-content: space-between; animation: slideIn 0.2s; }
    .item-info { flex: 1; }
    .item-name { font-weight: 600; font-size: 14px; color: #333; }
    .item-meta { font-size: 12px; color: #888; }
    .item-controls { display: flex; align-items: center; gap: 10px; margin: 0 15px; }
    .qty-btn { width: 28px; height: 28px; background: #f0f0f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #555; }
    .qty-val { font-weight: 600; font-size: 14px; width: 20px; text-align: center; }
    .item-price { font-weight: 700; color: #333; font-size: 15px; min-width: 60px; text-align: right; }
    .remove-btn { color: #ff5252; cursor: pointer; opacity: 0.5; transition: 0.2s; }
    .remove-btn:hover { opacity: 1; }
    
    .cart-footer { padding: 20px; background: #fff; border-top: 1px solid #eee; }
    .total-row { display: flex; justify-content: space-between; margin-top: 15px; margin-bottom: 20px; font-size: 20px; font-weight: 800; color: #333; }
    .checkout-btn { width: 100%; background: #00c853; color: #fff; border: none; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: 700; text-transform: uppercase; cursor: pointer; transition: 0.2s; }
    .checkout-btn:hover { background: #00e676; box-shadow: 0 5px 15px rgba(0,200,83,0.3); }
    .checkout-btn:disabled { background: #ccc; cursor: not-allowed; box-shadow: none; }
    
    /* Hold Sale Banner */
    .held-sale-alert {
        background-color: #fff3e0; border: 1px solid #ffe0b2; color: #e65100;
        padding: 10px 15px; margin: 10px 15px 0 15px; border-radius: 8px;
        display: flex; justify-content: space-between; align-items: center;
        cursor: pointer; display: none; animation: slideDown 0.3s;
    }
    
    /* Modals */
    .modal-content { border-radius: 12px; border: none; }
    .search-results-list { max-height: 300px; overflow-y: auto; list-style: none; padding: 0; margin: 0; }
    .result-item { padding: 12px 15px; border-bottom: 1px solid #eee; cursor: pointer; transition: 0.2s; }
    .result-item:hover { background: #f9f9f9; }
    
    .rx-details-box { display: none; } 
    
    .payment-method-btn { height: 100px; display: flex; flex-direction: column; justify-content: center; align-items: center; font-size: 16px; font-weight: bold; }
    .payment-method-btn i { font-size: 32px; margin-bottom: 10px; }
    .walk-in-form { display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; }
    
    .kbd-hint { background: #333; color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-family: monospace; margin-left: 8px; opacity: 0.7; }
    
    @keyframes slideIn { from { opacity: 0; transform: translateX(10px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes slideDown { from { transform: translateY(-10px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
</head>
<body>
    <div class="pos-topbar">
        <div class="brand-logo"><i class="zmdi zmdi-local-pharmacy"></i> Clinical Pro POS</div>
        <div class="user-profile">{{ Auth::user()->name }} ({{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }})</div>
    </div>
    
    <div class="pos-content">
        <div class="left-panel">
            <div class="search-row">
                <div class="search-card" onclick="document.getElementById('drug-search').focus()">
                    <i class="zmdi zmdi-search"></i>
                    <input type="text" id="drug-search" class="search-input" placeholder="Search drugs by name..."> <span class="kbd-hint">F1</span>
                </div>
                <div class="search-card" onclick="$('#patientModal').modal('show'); setTimeout(()=>$('#patient-search-input').focus(), 500);">
                    <i class="zmdi zmdi-account"></i>
                    <input type="text" readonly class="search-input" placeholder="Select patient / Walk-in..."> <span class="kbd-hint">F4</span>
                </div>
            </div>
            <div id="product-grid" class="product-grid">
                <div class="col-12 text-center text-muted p-4">Start typing to search for drugs...</div>
            </div>
        </div>
        
        <div class="right-panel">
            <div class="cart-header">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="text-muted small mb-0">CUSTOMER</label>
                    <div>
                        <button class="btn btn-sm btn-outline-warning mr-1" onclick="holdSale()" title="Hold Sale">
                            <i class="zmdi zmdi-pause"></i> <span class="kbd-hint">F8</span>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="clearCart()" title="Clear Cart"><i class="zmdi zmdi-delete"></i></button>
                    </div>
                </div>
                <div class="patient-selector" data-toggle="modal" data-target="#patientModal">
                    <span id="selected-customer">Walk-In Customer</span>
                    <i class="zmdi zmdi-chevron-down ml-1"></i>
                </div>
                <input type="hidden" id="customer-id" value="">
                <input type="hidden" id="prescription-id" value="">
            </div>

            <div id="held-sale-banner" class="held-sale-alert" onclick="resumeSale()">
                <div>
                    <strong><i class="zmdi zmdi-alert-circle-o"></i> Sale On Hold</strong><br>
                    <small id="held-sale-info">Customer: ...</small>
                </div>
                <button class="btn btn-sm btn-warning">Resume</button>
            </div>

            <div id="rx-info-panel" class="rx-details-box">
                <strong>Doctor's Instructions:</strong>
                <div id="rx-instructions-list"></div>
            </div>

            <div class="cart-body" id="cart-container">
                <div class="text-center text-muted mt-5 pt-5">Cart is empty</div>
            </div>

            <div class="cart-footer">
                <div class="total-row"><span>Total</span> <span id="total-display">₦0.00</span></div>
                <button class="checkout-btn" id="checkout-btn" disabled onclick="processSale()">
                    PROCESS PAYMENT <span class="kbd-hint bg-white text-dark">F9</span>
                </button>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="patientModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Select Customer</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body">
                    <div class="btn-group btn-group-toggle w-100 mb-3" data-toggle="buttons">
                        <label class="btn btn-outline-primary active" onclick="$('#search-mode-container').show(); $('#walk-in-mode-container').hide();"><input type="radio" checked> Search Database</label>
                        <label class="btn btn-outline-success" onclick="$('#search-mode-container').hide(); $('#walk-in-mode-container').show();"><input type="radio"> New Walk-In</label>
                    </div>
                    <div id="search-mode-container">
                        <input type="text" id="patient-search-input" class="form-control mb-3" placeholder="Type name, phone or ID...">
                        <ul class="search-results-list" id="patient-results-list"></ul>
                    </div>
                    <div id="walk-in-mode-container" class="walk-in-form">
                        <input type="text" id="walkin-name" class="form-control mb-2" placeholder="Customer Name *">
                        <input type="text" id="walkin-phone" class="form-control mb-2" placeholder="Phone (Optional)">
                        <button class="btn btn-success btn-block" onclick="saveWalkIn()">Register & Select</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Payment Method</h5></div>
                <div class="modal-body text-center">
                    <h3 class="text-primary mb-4" id="modal-total-display">₦0.00</h3>
                    <div class="row">
                        <div class="col-6 mb-3"><button class="btn btn-outline-success btn-lg btn-block payment-method-btn" onclick="confirmPayment('cash')"><i class="zmdi zmdi-money-box"></i> CASH</button></div>
                        <div class="col-6 mb-3"><button class="btn btn-outline-info btn-lg btn-block payment-method-btn" onclick="confirmPayment('pos')"><i class="zmdi zmdi-card"></i> POS</button></div>
                        <div class="col-6"><button class="btn btn-outline-warning btn-lg btn-block payment-method-btn" onclick="confirmPayment('transfer')"><i class="zmdi zmdi-smartphone-android"></i> TRANSFER</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> 
    <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    // --- KEYBOARD SHORTCUTS ---
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F1') { e.preventDefault(); $('#drug-search').focus(); }
        if (e.key === 'F4') { e.preventDefault(); $('#patientModal').modal('toggle'); setTimeout(() => $('#patient-search-input').focus(), 500); }
        if (e.key === 'F9') { e.preventDefault(); if(!$('#checkout-btn').prop('disabled')) processSale(); }
        if (e.key === 'F8') {
            e.preventDefault();
            // Toggle Logic: If we have a cart, hold it. If cart empty & held data exists, resume.
            const heldData = localStorage.getItem('held_sale');
            if (cart.length > 0) holdSale();
            else if (heldData) resumeSale();
            else Swal.fire('Notice', 'No active sale to hold, and no held sale to resume.', 'info');
        }
    });

    const rolePrefix = "{{ Auth::user()->role === 'primary_pharmacist' ? 'primary_pharmacist' : (Auth::user()->role === 'senior_pharmacist' ? 'senior_pharmacist' : 'clinic_pharmacist') }}";
    
    const searchDrugsUrl = `/` + rolePrefix + `/sales/search-drugs`;
    const searchPatientUrl = `/` + rolePrefix + `/sales/search-patient`;
    const loadPrescriptionUrlBase = `/` + rolePrefix + `/sales/load-prescription`;
    const processSaleUrl = `/` + rolePrefix + `/sales/process-sale`;
    const createWalkInUrl = `/` + rolePrefix + `/sales/create-walkin`;

    let cart = [];

    $(document).ready(function() {
        $('#drug-search').focus();
        checkHeldSale(); // Init Check
    });

    // 1. Drug Search with Stock Display
    $('#drug-search').on('input', function() {
        let query = $(this).val();
        if(query.length < 1) { $('#product-grid').html('<div class="col-12 text-center p-4">Start typing...</div>'); return; }
        
        clearTimeout($(this).data('timeout'));
        $(this).data('timeout', setTimeout(function() {
            $.get(searchDrugsUrl, { q: query }, function(data) {
                let html = '';
                if(data.length === 0) html = '<div class="col-12 text-center p-4">No drugs found.</div>';
                
                data.forEach(item => {
                    let stockClass = 'badge-stock-high';
                    if(item.stock <= 0) stockClass = 'badge-stock-out';
                    else if(item.stock < 20) stockClass = 'badge-stock-low';
                    
                    html += `
                    <div class="drug-card" onclick="addToCart(${item.id}, '${item.name.replace(/'/g, "\\'")}', ${item.price}, ${item.stock})">
                        <div style="position:relative;">
                            <div class="drug-name">${item.name}</div>
                            <span class="badge-stock ${stockClass}">${item.stock} Left</span>
                        </div>
                        <div class="drug-stock">SKU: ${item.id}</div>
                        <div class="drug-price">₦${formatMoney(item.price)}</div>
                    </div>`;
                });
                $('#product-grid').html(html);
            });
        }, 300));
    });

    // 2. Patient Search with Detailed Prescriptions
    $('#patient-search-input').on('input', function() {
        let query = $(this).val();
        if(query.length < 2) return;
        
        clearTimeout($(this).data('timeout'));
        $(this).data('timeout', setTimeout(function() {
            $.get(searchPatientUrl, { q: query }, function(data) {
                let html = '';
                data.forEach(pt => {
                    html += `<li class="result-item" onclick="selectPatient(${pt.id}, '${pt.name.replace(/'/g, "\\'")}')">
                        <strong>${pt.name}</strong> <small>${pt.phone||''}</small>`;
                    
                    if(pt.prescriptions && pt.prescriptions.length > 0) {
                        html += `<div class="mt-2 bg-light p-2 rounded">`;
                        pt.prescriptions.forEach(p => {
                            html += `<div class="mb-2 border-bottom pb-2">`;
                            html += `<strong>Rx #${p.id}</strong> <small class="text-muted">${new Date(p.created_at).toLocaleDateString()}</small><br>`;
                            p.items.forEach(item => {
                                let drugName = item.drug ? item.drug.name : 'Unknown Drug';
                                let dosage = item.dosage_display || 'As directed';
                                html += `<small>• ${drugName} (${item.quantity}) - ${dosage}</small><br>`;
                            });
                            html += `<button class="btn btn-xs btn-primary mt-1" onclick="event.stopPropagation(); loadPrescription(${p.id}, '${pt.name.replace(/'/g, "\\'")}')">Load This Rx</button>`;
                            html += `</div>`;
                        });
                        html += `</div>`;
                    }
                    html += `</li>`;
                });
                $('#patient-results-list').html(html);
            });
        }, 300));
    });

    // 3. Add to Cart with STRICT Stock Check
    function addToCart(id, name, price, maxStock) {
        if(maxStock <= 0) {
            Swal.fire('Out of Stock', 'This item cannot be sold until refilled.', 'error');
            return;
        }
        let existing = cart.find(i => i.id === id);
        if(existing) {
            if(existing.qty < maxStock) {
                existing.qty++;
            } else {
                Swal.fire('Limit Reached', 'Cannot exceed available stock.', 'warning');
            }
        } else {
            cart.push({ id, name, price: parseFloat(price), qty: 1, max: maxStock });
        }
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-container');
        container.innerHTML = '';
        let total = 0;
        
        if(cart.length === 0) {
            container.innerHTML = '<div class="text-center mt-5">Cart is empty</div>';
            $('#checkout-btn').prop('disabled', true);
            $('#total-display').text('₦0.00');
            return;
        }

        cart.forEach((item, i) => {
            total += item.price * item.qty;
            container.innerHTML += `
            <div class="cart-item">
                <div class="item-info">
                    <div class="item-name">${item.name}</div>
                    <div class="item-meta">₦${formatMoney(item.price)} x ${item.qty}</div>
                </div>
                <div class="item-controls">
                    <div class="qty-btn" onclick="updateQty(${i}, -1)">-</div>
                    <div class="qty-val">${item.qty}</div>
                    <div class="qty-btn" onclick="updateQty(${i}, 1)">+</div>
                </div>
                <div class="item-price">₦${formatMoney(item.price * item.qty)}</div>
                <div class="remove-btn ml-3" onclick="removeItem(${i})"><i class="zmdi zmdi-close"></i></div>
            </div>`;
        });
        
        $('#total-display').text('₦' + formatMoney(total));
        $('#modal-total-display').text('₦' + formatMoney(total));
        $('#checkout-btn').prop('disabled', false);
    }

    function updateQty(i, change) {
        let item = cart[i];
        let newQty = item.qty + change;
        if(newQty > item.max) {
            Swal.fire('Limit Reached', 'Cannot exceed available stock.', 'warning');
            return;
        }
        if(newQty > 0) item.qty = newQty;
        renderCart();
    }
    
    function removeItem(i) { cart.splice(i, 1); renderCart(); }
    function formatMoney(n) { return parseFloat(n).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); }

    window.selectPatient = function(id, name) {
        $('#patientModal').modal('hide');
        $('#selected-customer').text(name);
        $('#customer-id').val(id);
        $('#prescription-id').val('');
    }

    window.loadPrescription = function(id, name) {
        $('#patientModal').modal('hide');
        $('#selected-customer').text(name);
        $('#prescription-id').val(id);
        
        $.get(`${loadPrescriptionUrlBase}/${id}`, function(res) {
            if(res.status === 'success') {
                cart = [];
                res.items.forEach(i => {
                    if(i.stock_check > 0) {
                        let qty = Math.min(i.quantity, i.stock_check);
                        cart.push({
                            id: i.id,
                            name: i.name,
                            price: i.price,
                            qty: qty,
                            max: i.stock_check
                        });
                    }
                });
                
                if(cart.length === 0) {
                    Swal.fire('Warning', 'All items in this prescription are out of stock.', 'warning');
                } else if(cart.length < res.items.length) {
                    Swal.fire('Notice', 'Some items were omitted because they are out of stock.', 'info');
                }
                renderCart();
            }
        });
    }

    window.saveWalkIn = function() {
        let name = $('#walkin-name').val();
        if(!name) return alert('Name required');
        
        $.post(createWalkInUrl, {
            name: name,
            phone: $('#walkin-phone').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function(res) {
            selectPatient(res.user_id, res.name);
        });
    }

    window.processSale = function() { $('#paymentModal').modal('show'); }

    window.confirmPayment = function(method) {
        $('#paymentModal').modal('hide');
        $('#checkout-btn').prop('disabled', true).text('Processing...');
        
        let total = $('#total-display').text().replace('₦','').replace(/,/g,'');
        
        $.post(processSaleUrl, {
            patient_id: $('#customer-id').val(),
            prescription_id: $('#prescription-id').val(),
            items: cart,
            total: total,
            payment_method: method,
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function(res) {
            if(res.status === 'success') {
                Swal.fire({
                    title: 'Success!', text: 'Print receipt?', icon: 'success',
                    showCancelButton: true, confirmButtonText: 'Print'
                }).then((r) => {
                    if(r.isConfirmed) window.open(res.receipt_url, '_blank');
                    window.location.reload(); 
                });
            } else {
                Swal.fire('Error', res.message || 'Failed', 'error');
                $('#checkout-btn').prop('disabled', false).text('PROCESS PAYMENT');
            }
        }).fail((xhr) => {
            Swal.fire('Error', xhr.responseJSON.message || 'Server Error', 'error');
            $('#checkout-btn').prop('disabled', false).text('PROCESS PAYMENT');
        });
    }

    // --- Hold/Resume (LocalStorage) ---
    function checkHeldSale() {
        let data = localStorage.getItem('held_sale');
        if(data) {
            let d = JSON.parse(data);
            $('#held-sale-info').text(d.customer + ' (' + d.timestamp + ')');
            $('#held-sale-banner').slideDown();
        } else {
            $('#held-sale-banner').slideUp();
        }
    }
    window.holdSale = function() {
        if(cart.length === 0) return;
        let data = {
            cart: cart,
            customer: $('#selected-customer').text(),
            custId: $('#customer-id').val(),
            rxId: $('#prescription-id').val(),
            timestamp: new Date().toLocaleTimeString()
        };
        localStorage.setItem('held_sale', JSON.stringify(data));
        cart = []; renderCart(); checkHeldSale();
        Swal.fire('Held', 'Sale put on hold.', 'info');
    }
    window.resumeSale = function() {
        if(cart.length > 0) {
            if(!confirm('Clear current cart to resume?')) return;
        }
        let data = JSON.parse(localStorage.getItem('held_sale'));
        cart = data.cart;
        $('#selected-customer').text(data.customer);
        $('#customer-id').val(data.custId);
        $('#prescription-id').val(data.rxId);
        localStorage.removeItem('held_sale');
        renderCart(); checkHeldSale();
    }
    window.clearCart = function() {
        cart = []; renderCart();
        $('#selected-customer').text('Walk-In Customer');
        $('#customer-id').val('');
        $('#prescription-id').val('');
        $('#rx-info-panel').hide();
    }
    </script>
</body>
</html>