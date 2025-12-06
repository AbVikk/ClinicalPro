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
    body { background-color: #f4f7f6; overflow: hidden; font-family: 'Segoe UI', sans-serif; }
    .pos-container { display: flex; height: 100vh; width: 100vw; }
    
    /* Top Bar (Minimal) */
    .pos-topbar {
        position: fixed; top: 0; left: 0; width: 100%; height: 60px;
        background: #007bff; color: #fff; z-index: 1000;
        display: flex; align-items: center; justify-content: space-between; padding: 0 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .brand-logo { font-size: 20px; font-weight: 700; letter-spacing: 1px; }
    .user-profile { font-size: 14px; }
    
    /* Layout Columns */
    .pos-content { display: flex; width: 100%; margin-top: 60px; height: calc(100vh - 60px); }
    
    /* LEFT: Product/Queue Area */
    .left-panel { flex: 65%; padding: 20px; display: flex; flex-direction: column; overflow: hidden; }
    
    .search-row { display: flex; gap: 15px; margin-bottom: 20px; }
    .search-card { background: #fff; padding: 15px; border-radius: 10px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; align-items: center; cursor: text; transition: 0.2s; }
    .search-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .search-card i { font-size: 20px; color: #999; margin-right: 10px; }
    .search-input { border: none; outline: none; width: 100%; font-size: 15px; color: #333; }
    
    /* Product Grid */
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; overflow-y: auto; padding-bottom: 50px; }
    .drug-card { 
        background: #fff; border-radius: 10px; padding: 15px; 
        cursor: pointer; transition: transform 0.1s, box-shadow 0.1s; 
        border: 1px solid #eee; display: flex; flex-direction: column; justify-content: space-between; height: 130px;
    }
    .drug-category {
        margin-top: 2px;
    }
    .drug-card:active { transform: scale(0.98); }
    .drug-card:hover { border-color: #007bff; box-shadow: 0 5px 15px rgba(0,123,255,0.1); }
    .drug-name { font-weight: 600; font-size: 14px; color: #333; line-height: 1.3; margin-bottom: 5px; }
    .drug-stock { font-size: 11px; color: #888; }
    .drug-price { font-weight: 700; color: #007bff; font-size: 16px; margin-top: auto; }
    .badge-stock { position: absolute; top: 10px; right: 10px; font-size: 10px; padding: 3px 6px; border-radius: 4px; background: #e3f2fd; color: #007bff; }
    
    /* RIGHT: Cart Area */
    .right-panel { flex: 35%; background: #fff; border-left: 1px solid #e0e0e0; display: flex; flex-direction: column; box-shadow: -5px 0 20px rgba(0,0,0,0.05); }
    
    .cart-header { padding: 20px; border-bottom: 1px solid #f0f0f0; background: #fafafa; }
    .patient-selector { 
        background: #e3f2fd; color: #007bff; padding: 10px 15px; border-radius: 8px; 
        cursor: pointer; display: flex; align-items: center; justify-content: space-between;
        font-weight: 600; transition: 0.2s;
    }
    .patient-selector:hover { background: #bbdefb; }
    
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
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; color: #666; font-size: 14px; }
    .total-row { display: flex; justify-content: space-between; margin-top: 15px; margin-bottom: 20px; font-size: 20px; font-weight: 800; color: #333; }
    .checkout-btn { width: 100%; background: #00c853; color: #fff; border: none; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: 700; text-transform: uppercase; cursor: pointer; transition: 0.2s; }
    .checkout-btn:hover { background: #00e676; box-shadow: 0 5px 15px rgba(0,200,83,0.3); }
    .checkout-btn:disabled { background: #ccc; cursor: not-allowed; box-shadow: none; }
    
    /* Modals */
    .modal-content { border-radius: 12px; border: none; }
    .search-results-list { max-height: 300px; overflow-y: auto; list-style: none; padding: 0; margin: 0; }
    .result-item { padding: 12px 15px; border-bottom: 1px solid #eee; cursor: pointer; transition: 0.2s; }
    .result-item:hover { background: #f9f9f9; }
    
    @keyframes slideIn { from { opacity: 0; transform: translateX(10px); } to { opacity: 1; transform: translateX(0); } }
    
    /* NEW: Prescription Details Box */
    .rx-details-box {
        background: #fff3cd; 
        border: 1px solid #ffeeba; 
        color: #856404;
        padding: 10px 15px;
        margin: 10px 15px 0 15px;
        border-radius: 8px;
        font-size: 13px;
        display: none; /* Hidden by default */
    }
    .rx-details-box strong { display: block; margin-bottom: 5px; color: #533f03; }
    .rx-item-instruction { margin-bottom: 3px; padding-bottom: 3px; border-bottom: 1px dashed #ffeeba; }
    .rx-item-instruction:last-child { border-bottom: none; }

    /* Walk-In Form Styles */
    .walk-in-form { display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; }
    
    /* Payment Modal Styles */
    .payment-method-btn {
        height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        font-weight: bold;
    }
    .payment-method-btn i {
        font-size: 32px;
        margin-bottom: 10px;
    }
    
    /* NEW: Hold Sale Styles */
    .btn-hold { 
        background-color: #ff9800; 
        color: white; 
        border: none; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
    }
    .btn-hold:hover { background-color: #f57c00; color: white; }
    
    .held-sale-alert {
        background-color: #fff3e0;
        border: 1px solid #ffe0b2;
        color: #e65100;
        padding: 10px 15px;
        margin: 10px 15px 0 15px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        display: none; /* Hidden by default */
        animation: slideDown 0.3s;
    }
    .held-sale-alert:hover { background-color: #ffe0b2; }

    /* Keyboard Hints */
    .kbd-hint {
        background: #333;
        color: #fff;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-family: monospace;
        margin-left: 8px;
        opacity: 0.7;
        vertical-align: middle;
    }
    
    @keyframes slideDown { from { transform: translateY(-10px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
</head>
<body>
    <!-- Top Navigation -->
    <div class="pos-topbar">
        <div class="brand-logo"><i class="zmdi zmdi-local-pharmacy"></i> Clinical Pro POS</div>
        <div class="user-profile">{{ Auth::user()->name }}</div>
    </div>
    
    <!-- Main Content -->
    <div class="pos-content">
        <!-- LEFT PANEL: PRODUCT SEARCH & QUEUE -->
        <div class="left-panel">
            <div class="search-row">
                <!-- Drug Search -->
                <div class="search-card" onclick="document.getElementById('drug-search').focus()">
                    <i class="zmdi zmdi-search"></i>
                    <input type="text" id="drug-search" class="search-input" placeholder="Search drugs by name or SKU..."> <span class="kbd-hint">F1</span>
                </div>
                
                <!-- Patient Search -->
                <div class="search-card" onclick="$('#patientModal').modal('show'); document.getElementById('patient-search-input').focus()">
                    <i class="zmdi zmdi-account"></i>
                    <input type="text" readonly class="search-input" placeholder="Select patient / Walk-in..."> <span class="kbd-hint">F4</span>
                </div>
            </div>
            
            <!-- Product Grid -->
            <div id="product-grid" class="product-grid">
                <!-- Drugs will be populated here dynamically -->
                <div class="col-12 text-center text-muted p-4">Start typing to search for drugs...</div>
            </div>
        </div>
        
        <!-- RIGHT PANEL: CART -->
        <div class="right-panel">
            <div class="cart-header">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="text-muted small mb-0">CUSTOMER / PRESCRIPTION</label>
                    <div>
                        <button class="btn btn-sm btn-hold btn-round mr-1" onclick="holdSale()" title="Park current sale (F8)">
                            <i class="zmdi zmdi-pause"></i> Hold <span class="kbd-hint">F8</span>
                        </button>
                        <button class="btn btn-sm btn-danger btn-round" onclick="clearCart()" title="Clear Cart (Esc)">
                            <i class="zmdi zmdi-delete"></i>
                        </button>
                    </div>
                </div>
                
                <div class="patient-selector" data-toggle="modal" data-target="#patientModal" title="Change Customer (F4)">
                    <span id="selected-customer">Walk-In Customer</span>
                    <div>
                        <span class="kbd-hint">F4</span>
                        <i class="zmdi zmdi-chevron-down ml-1"></i>
                    </div>
                </div>
                <input type="hidden" id="customer-id" value="">
                <input type="hidden" id="prescription-id" value="">
            </div>

            <div id="held-sale-banner" class="held-sale-alert" onclick="resumeSale()">
                <div>
                    <strong><i class="zmdi zmdi-alert-circle-o"></i> Sale On Hold</strong><br>
                    <small id="held-sale-info">Customer: ...</small>
                </div>
                <button class="btn btn-sm btn-outline-warning text-dark bg-white">Resume <span class="kbd-hint">F8</span></button>
            </div>

            <div id="rx-info-panel" class="rx-details-box">
                <strong><i class="zmdi zmdi-info-outline"></i> Doctor's Instructions:</strong>
                <div id="rx-instructions-list"></div>
            </div>

            <div class="cart-body" id="cart-container">
                <div class="text-center text-muted mt-5 pt-5">
                    <i class="zmdi zmdi-shopping-basket" style="font-size: 40px; opacity: 0.3;"></i>
                    <p class="mt-3">Cart is empty</p>
                </div>
            </div>

            <div class="cart-footer">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="subtotal-display">₦0.00</span>
                </div>
                <div class="summary-row">
                    <span>Tax (0%)</span> 
                    <span>₦0.00</span>
                </div>
                <div class="total-row">
                    <span>Total</span>
                    <span id="total-display">₦0.00</span>
                </div>
                <button class="checkout-btn" id="checkout-btn" disabled onclick="processSale()">
                    <i class="zmdi zmdi-money"></i> PROCESS PAYMENT <span class="kbd-hint bg-white text-dark">F9</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Patient Selection Modal -->
    <div class="modal fade" id="patientModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    
                    <div class="btn-group btn-group-toggle w-100 mb-3" data-toggle="buttons">
                        <label class="btn btn-outline-primary active" onclick="showSearchMode()">
                            <input type="radio" name="options" autocomplete="off" checked> Search Database
                        </label>
                        <label class="btn btn-outline-success" onclick="showWalkInMode()">
                            <input type="radio" name="options" autocomplete="off"> New Walk-In
                        </label>
                    </div>

                    <div id="search-mode-container">
                        <input type="text" id="patient-search-input" class="form-control mb-3" placeholder="Type name, phone or ID to search...">
                        <div class="text-muted small mb-2">RECENT PENDING PRESCRIPTIONS</div>
                        <ul class="search-results-list" id="patient-results-list">
                            <!-- Patient results will be populated here -->
                        </ul>
                    </div>

                    <div id="walk-in-mode-container" class="walk-in-form">
                        <h6 class="mb-3">Quick Register (Walk-In)</h6>
                        <div class="form-group">
                            <label>Customer Name *</label>
                            <input type="text" id="walkin-name" class="form-control" placeholder="e.g. John Doe">
                        </div>
                        <div class="form-group">
                            <label>Phone Number (Optional)</label>
                            <input type="text" id="walkin-phone" class="form-control" placeholder="e.g. 080...">
                        </div>
                        <button class="btn btn-success btn-block" onclick="saveWalkIn()">Save & Select for Sale</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <!-- Payment Method Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Payment Method</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body text-center">
                    <h3 class="text-primary mb-4" id="modal-total-display">₦0.00</h3>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <button class="btn btn-outline-success btn-lg btn-block py-3 payment-method-btn" onclick="confirmPayment('cash')">
                                <i class="zmdi zmdi-money-box"></i> CASH
                            </button>
                        </div>
                        <div class="col-6 mb-3">
                            <button class="btn btn-outline-info btn-lg btn-block py-3 payment-method-btn" onclick="confirmPayment('pos')">
                                <i class="zmdi zmdi-card"></i> POS / CARD
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-outline-warning btn-lg btn-block py-3 payment-method-btn" onclick="confirmPayment('transfer')">
                                <i class="zmdi zmdi-smartphone-android"></i> TRANSFER
                            </button>
                        </div>
                        <!-- Only show Paystack if needed -->
                        <!-- <div class="col-6"> ... Paystack Button ... </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> 
    <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    // --- POS JAVASCRIPT LOGIC ---
    // Defined Route Variables for JS
    const searchDrugsUrl = "{{ route('primary_pharmacist.sales.search-drugs') }}";
    const searchPatientUrl = "{{ route('primary_pharmacist.sales.search-patient') }}";
    const loadPrescriptionUrlBase = "{{ url('/primary_pharmacist/sales/load-prescription') }}";
    const processSaleUrl = "{{ route('primary_pharmacist.sales.process-sale') }}";
    const createWalkInUrl = "{{ route('primary_pharmacist.sales.create-walkin') }}";
    
    // Debug logging for URLs
    console.log('Search Drugs URL:', searchDrugsUrl);
    console.log('Search Patient URL:', searchPatientUrl);

    // Create completely isolated AJAX functions that bypass all external library interference
    function makeAjaxRequest(url, method, data, callback) {
        // Use the native fetch API to completely bypass jQuery and external libraries
        let finalUrl = url;
        let options = {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        };
        
        if (method === 'GET' && data) {
            // Build query string for GET requests
            const params = new URLSearchParams();
            for (let key in data) {
                if (data.hasOwnProperty(key)) {
                    params.append(key, data[key]);
                }
            }
            finalUrl = url + '?' + params.toString();
        } else if (method === 'POST' && data) {
            // Set content type and body for POST requests
            options.headers['Content-Type'] = 'application/x-www-form-urlencoded';
            const params = new URLSearchParams();
            for (let key in data) {
                if (data.hasOwnProperty(key)) {
                    params.append(key, data[key]);
                }
            }
            options.body = params.toString();
        }
        
        console.log('Making fetch request to:', finalUrl); // Debug log
        console.log('Request options:', options); // Debug log
        
        fetch(finalUrl, options)
            .then(response => {
                console.log('Fetch response status:', response.status); // Debug log
                console.log('Fetch response headers:', [...response.headers.entries()]); // Debug log
                return response.json();
            })
            .then(data => {
                console.log('Fetch success:', data); // Debug log
                callback(null, data);
            })
            .catch(error => {
                console.error('Fetch error:', error); // Debug log
                callback(error, null);
            });
    }

    let cart = [];
    const cartContainer = document.getElementById('cart-container');
    const totalDisplay = document.getElementById('total-display');
    const subtotalDisplay = document.getElementById('subtotal-display');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    // Initialize when DOM is ready and jQuery is available
    $(document).ready(function() {
        initializePOS();
        // Set up keyboard shortcuts after DOM is ready
        setupKeyboardShortcuts();
    });
    
    function initializePOS() {
        // Initialize with empty product grid
        $('#product-grid').html('<div class="col-12 text-center text-muted p-4">Start typing to search for drugs...</div>');
        
        // Focus search box after a short delay to ensure page is fully loaded
        setTimeout(function() {
            $('#drug-search').focus();
        }, 100);
        
        // Render empty cart
        renderCart();
        
        // Setup event listeners
        setupEventListeners();
        
        // Check for held sales
        checkHeldSale();
    }
    
    function setupEventListeners() {
        // 4. LIVE DRUG SEARCH
        $('#drug-search').on('input', function() {
            let query = $(this).val();
            if(query.length < 1) {
                // Show empty state when search is cleared
                $('#product-grid').html('<div class="col-12 text-center text-muted p-4">Start typing to search for drugs...</div>');
                return;
            }

            // Add debounce to reduce API calls
            clearTimeout($(this).data('timeout'));
            $(this).data('timeout', setTimeout(function() {
                // Use our isolated AJAX function to prevent external library interference
                const request = makeAjaxRequest(searchDrugsUrl, 'GET', { q: query }, function(error, data) {
                    console.log('Drug search response:', data); // Debug log
                    if (error) {
                        console.error('Drug search error:', error);
                        $('#product-grid').html('<div class="col-12 text-center text-danger p-4">Error loading drugs. Please try again.</div>');
                        return;
                    }
                    
                    // Ensure data is an array
                    if (!Array.isArray(data)) {
                        console.error('Expected array but got:', typeof data, data);
                        $('#product-grid').html('<div class="col-12 text-center text-danger p-4">Error loading drugs. Invalid response format.</div>');
                        return;
                    }
                    
                    let gridHtml = '';
                    if(data.length === 0) gridHtml = '<div class="col-12 text-center text-muted p-4">No drugs found.</div>';
                    
                    data.forEach(item => {
                        gridHtml += `
                        <div class="drug-card" onclick="addToCart(${item.id}, '${item.name.replace(/'/g, "\\'")}', ${item.price}, ${item.stock})">
                            <div style="position:relative;">
                                <div class="drug-name">${item.name}</div>
                                <span class="badge-stock">${item.stock}</span>
                            </div>
                            <div class="drug-stock">SKU: ${item.id}</div>
                            <div class="drug-price">₦${formatMoney(item.price)}</div>
                        </div>`;
                    });
                    $('#product-grid').html(gridHtml);
                });
            }, 300)); // 300ms debounce
        });

        // 5. PATIENT SEARCH (Existing Logic)
        $('#patient-search-input').on('input', function() {
            let query = $(this).val();
            if(query.length < 2) return;

            // Add debounce to reduce API calls
            clearTimeout($(this).data('timeout'));
            $(this).data('timeout', setTimeout(function() {
                // Use our isolated AJAX function to prevent external library interference
                const request = makeAjaxRequest(searchPatientUrl, 'GET', { q: query }, function(error, data) {
                    console.log('Patient search response:', data); // Debug log
                    if (error) {
                        console.error('Patient search error:', error);
                        $('#patient-results-list').html('<li class="result-item text-danger">Error loading patients. Please try again.</li>');
                        return;
                    }
                    
                    // Ensure data is an array
                    if (!Array.isArray(data)) {
                        console.error('Expected array but got:', typeof data, data);
                        $('#patient-results-list').html('<li class="result-item text-danger">Error loading patients. Invalid response format.</li>');
                        return;
                    }
                    
                    let html = '';
                    data.forEach(patient => {
                        // Show patient details
                        html += `
                        <li class="result-item" onclick="selectPatient(${patient.id}, '${patient.name.replace(/'/g, "\\'")}')">
                            <div class="d-flex justify-content-between">
                                <strong>${patient.name}</strong>
                            </div>
                            <small class="text-muted">${patient.phone || 'No Phone'}</small>
                        `;
                        
                        // Show prescriptions with drugs and dosages if they exist
                        if(patient.prescriptions && patient.prescriptions.length > 0) {
                            html += `<div class="mt-2">`;
                            patient.prescriptions.forEach(p => {
                                html += `<div class="border rounded p-2 mb-2">`;
                                html += `<div class="d-flex justify-content-between">`;
                                html += `  <span class="badge badge-info">Rx #${p.id}</span>`;
                                html += `  <small class="text-muted">${new Date(p.created_at).toLocaleDateString()}</small>`;
                                html += `</div>`;
                                
                                // Show prescription items with dosages
                                if(p.items && p.items.length > 0) {
                                    html += `<div class="mt-1">`;
                                    p.items.forEach(item => {
                                        let dosageInfo = '';
                                        if (item.dosage_instructions) {
                                            const parts = item.dosage_instructions.split('||');
                                            const dosage = parts[0] || '';
                                            const type = parts[1] || '';
                                            const duration = parts[2] || '';
                                            const usePattern = parts[3] || '';
                                            const instructions = parts[4] || '';
                                            
                                            dosageInfo = `${dosage} ${type}`;
                                            if (duration) dosageInfo += `, for ${duration}`;
                                            if (usePattern) dosageInfo += `, ${usePattern}`;
                                        }
                                        
                                        html += `<div class="small">`;
                                        html += `  <strong>${item.medication_name || (item.drug ? item.drug.name : 'Unknown')}</strong>`;
                                        html += `  <span class="ml-1">(${item.quantity})</span>`;
                                        if (dosageInfo) {
                                            html += `  <br><span class="text-muted">${dosageInfo}</span>`;
                                        }
                                        html += `</div>`;
                                    });
                                    html += `</div>`;
                                }
                                
                                html += `<button class="btn btn-sm btn-info mt-2" onclick="event.stopPropagation(); loadPrescription(${p.id}, '${patient.name.replace(/'/g, "\\'")}')">Load Prescription</button>`;
                                html += `</div>`;
                            });
                            html += `</div>`;
                        }
                        html += `</li>`;
                    });
                    $('#patient-results-list').html(html);
                });
            }, 300)); // 300ms debounce
        });
    }
    
    // Setup keyboard shortcuts
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            
            // F1: Focus Drug Search
            if (e.key === 'F1') {
                e.preventDefault();
                $('#drug-search').focus();
            }
            
            // F4: Open Patient Modal
            if (e.key === 'F4') {
                e.preventDefault();
                $('#patientModal').modal('toggle');
                setTimeout(() => $('#patient-search-input').focus(), 500);
            }

            // F8: Hold Sale or Resume Held Sale
            if (e.key === 'F8') {
                e.preventDefault();
                // Check if there's a held sale to resume
                const heldData = localStorage.getItem('held_pos_sale');
                if (heldData && cart.length === 0) {
                    resumeSale();
                } else if (cart.length > 0) {
                    holdSale();
                } else {
                    alert('No sale to hold or resume.');
                }
            }

            // F9: Process Payment (Only if cart has items)
            if (e.key === 'F9') {
                e.preventDefault();
                if(!$('#checkout-btn').prop('disabled')) {
                    processSale();
                }
            }

            // Esc: Clear Search or Cart
            if (e.key === 'Escape') {
                // If modal is open, let Bootstrap handle it.
                // If search is focused, blur it.
                if (document.activeElement.id === 'drug-search') {
                    $('#drug-search').val('').blur();
                    return;
                }
                // If nothing else, ask to clear cart
                if (cart.length > 0 && !$('#paymentModal').hasClass('show')) {
                    if(confirm('Clear current cart?')) clearCart();
                }
            }
        });
    }
    
    // 1. ADD TO CART
    window.addToCart = function(id, name, price, maxStock) {
        // Ensure values are numbers
        const itemPrice = typeof price === 'number' ? price : (parseFloat(price) || 0);
        const itemMaxStock = typeof maxStock === 'number' ? maxStock : (parseFloat(maxStock) || 0);
        
        let existing = cart.find(i => i.id === id);
        if(existing) {
            // Allow increasing quantity even if stock is 0
            if(itemMaxStock === 0 || existing.qty < itemMaxStock) {
                existing.qty++;
            } else {
                alert('Max stock reached for this item.');
            }
        } else {
            // Allow adding items with 0 stock
            cart.push({ 
                id: id, 
                name: itemMaxStock === 0 ? name + ' (Out of Stock)' : name, 
                price: itemPrice, 
                qty: 1, 
                max: itemMaxStock 
            });
        }
        renderCart();
    }

    // 2. RENDER CART
    function renderCart() {
        cartContainer.innerHTML = '';
        let total = 0;

        if(cart.length === 0) {
            cartContainer.innerHTML = `
                <div class="text-center text-muted mt-5 pt-5">
                    <i class="zmdi zmdi-shopping-basket" style="font-size: 40px; opacity: 0.3;"></i>
                    <p class="mt-3">Cart is empty</p>
                </div>`;
            checkoutBtn.disabled = true;
            totalDisplay.innerText = '₦0.00';
            subtotalDisplay.innerText = '₦0.00';
            return;
        }

        cart.forEach((item, index) => {
            // Ensure price is a number
            const itemPrice = typeof item.price === 'number' ? item.price : (parseFloat(item.price) || 0);
            const itemQty = typeof item.qty === 'number' ? item.qty : (parseFloat(item.qty) || 0);
            const itemTotal = itemPrice * itemQty;
            
            total += itemTotal;
            let itemHtml = `
            <div class="cart-item">
                <div class="item-info">
                    <div class="item-name">${item.name}</div>
                    <div class="item-meta">₦${formatMoney(itemPrice)} × ${itemQty}</div>
                </div>
                <div class="item-controls">
                    <div class="qty-btn" onclick="updateQty(${index}, -1)">-</div>
                    <div class="qty-val">${itemQty}</div>
                    <div class="qty-btn" onclick="updateQty(${index}, 1)">+</div>
                </div>
                <div class="item-price">₦${formatMoney(itemTotal)}</div>
                <div class="remove-btn ml-3" onclick="removeItem(${index})"><i class="zmdi zmdi-close"></i></div>
            </div>`;
            cartContainer.innerHTML += itemHtml;
        });

        totalDisplay.innerText = '₦' + formatMoney(total);
        subtotalDisplay.innerText = '₦' + formatMoney(total);
        checkoutBtn.disabled = false;
        checkoutBtn.innerHTML = `<i class="zmdi zmdi-money"></i> PROCESS PAYMENT <span class="kbd-hint bg-white text-dark">F9</span>`;
    }

    // 3. UTILITIES
    window.updateQty = function(index, change) {
        let item = cart[index];
        // Ensure values are numbers
        const currentQty = typeof item.qty === 'number' ? item.qty : (parseFloat(item.qty) || 0);
        const maxStock = typeof item.max === 'number' ? item.max : (parseFloat(item.max) || 0);
        let newQty = currentQty + change;
        
        // Allow changing quantity even for out of stock items, but respect max stock if > 0
        if(newQty > 0 && (maxStock === 0 || newQty <= maxStock)) {
            item.qty = newQty;
        } else if (newQty > maxStock && maxStock > 0) {
            alert('Insufficient stock!');
        }
        renderCart();
    }

    window.removeItem = function(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function formatMoney(n) {
        // Ensure n is a number, default to 0 if not
        const num = typeof n === 'number' ? n : (parseFloat(n) || 0);
        return num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    // Select Patient (No Prescription)
    window.selectPatient = function(id, name) {
        $('#patientModal').modal('hide');
        document.getElementById('selected-customer').innerText = name;
        document.getElementById('customer-id').value = id;
        document.getElementById('prescription-id').value = ''; // Clear Rx
        $('#rx-info-panel').hide(); // Hide instructions
    }

    // 6. LOAD PRESCRIPTION & SHOW INSTRUCTIONS
    window.loadPrescription = function(id, name) {
        $('#patientModal').modal('hide');
        document.getElementById('selected-customer').innerText = name;
        document.getElementById('prescription-id').value = id;

        // Use explicit AJAX settings to prevent external library interference
        $.ajax({
            url: `${loadPrescriptionUrlBase}/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.status === 'success') {
                    cart = []; // Clear existing
                    let instructionsHtml = '';

                    data.items.forEach(i => {
                        // Add to cart logic - ALLOW ITEMS WITH 0 STOCK
                        // Use prescribed qty, but cap at stock if stock > 0
                        let qty = i.quantity;
                        let maxStock = i.stock_check;
                        
                        // If stock is 0, still add item but mark it as out of stock
                        if(i.stock_check === 0) {
                            cart.push({ 
                                id: i.id, 
                                name: i.name + ' (Out of Stock)', 
                                price: i.price, 
                                qty: qty, 
                                max: 0 
                            });
                        } else {
                            // Cap qty at available stock
                            qty = Math.min(i.quantity, i.stock_check);
                            cart.push({ 
                                id: i.id, 
                                name: i.name, 
                                price: i.price, 
                                qty: qty, 
                                max: i.stock_check 
                            });
                        }
                        
                        // Build Instruction Text with all details
                        let instructionText = '';
                        if (i.dosage || i.type || i.duration || i.use_pattern || i.instructions) {
                            instructionText = `${i.dosage ? i.dosage + ' ' + i.type : ''}`;
                            if (i.duration) instructionText += `, for ${i.duration}`;
                            if (i.use_pattern) instructionText += `, ${i.use_pattern}`;
                            if (i.instructions) instructionText += `, ${i.instructions}`;
                        } else {
                            instructionText = 'As directed';
                        }
                        
                        instructionsHtml += `<div class="rx-item-instruction">● <strong>${i.name}:</strong> ${instructionText}</div>`;
                    });

                    renderCart();
                    
                    // Show Instructions Panel
                    if(instructionsHtml) {
                        $('#rx-instructions-list').html(instructionsHtml);
                        $('#rx-info-panel').slideDown();
                    } else {
                        $('#rx-info-panel').hide();
                    }

                } else {
                    alert(data.message || 'Error loading prescription');
                }
            },
            error: function() {
                alert('Failed to load prescription. Please try again.');
            }
        });
    }

    // 7. WALK-IN CUSTOMER LOGIC
    window.showSearchMode = function() {
        $('#walk-in-mode-container').hide();
        $('#search-mode-container').show();
    }
    window.showWalkInMode = function() {
        $('#search-mode-container').hide();
        $('#walk-in-mode-container').show();
    }

    window.saveWalkIn = function() {
        let name = $('#walkin-name').val();
        let phone = $('#walkin-phone').val();

        if(!name) { alert('Name is required'); return; }

        // AJAX to create temp user or get ID with explicit settings
        $.ajax({
            url: createWalkInUrl,
            type: 'POST',
            data: { 
                name: name, 
                phone: phone,
                _token: $('meta[name="csrf-token"]').attr('content') 
            },
            dataType: 'json',
            success: function(res) {
                // Select this new user
                selectPatient(res.user_id, res.name);
                // Clear form
                $('#walkin-name').val('');
                $('#walkin-phone').val('');
                // Switch back to search mode for next time
                showSearchMode(); 
                $('.btn-group-toggle label').removeClass('active').first().addClass('active');
            },
            error: function() {
                alert('Failed to save walk-in customer.');
            }
        });
    }

    // 8. PAYMENT LOGIC
    // 1. Trigger Modal
    window.processSale = function() {
        let totalText = totalDisplay.innerText;
        $('#modal-total-display').text(totalText);
        $('#paymentModal').modal('show');
    }

    // 2. Handle Payment Selection
    window.confirmPayment = function(method) {
        $('#paymentModal').modal('hide');
        
        let total = totalDisplay.innerText.replace('₦', '').replace(/,/g, '');
        let payload = {
            patient_id: document.getElementById('customer-id').value,
            prescription_id: document.getElementById('prescription-id').value,
            items: cart,
            total: total,
            payment_method: method, // <-- Sending method
            _token: "{{ csrf_token() }}"
        };

        // Disable button to prevent double-submit
        $('#checkout-btn').prop('disabled', true).text('Processing...');

        // Use explicit AJAX settings
        $.ajax({
            url: processSaleUrl,
            type: 'POST',
            data: payload,
            dataType: 'json',
            success: function(res) {
                if(res.status === 'success') {
                    // Success!
                    Swal.fire({ // Assuming SweetAlert or use native confirm
                        title: 'Payment Successful!',
                        text: 'Do you want to print the receipt?',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Print Receipt',
                        cancelButtonText: 'New Sale'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Open PDF in new tab/window for printing
                            window.open(res.receipt_url, '_blank');
                        }
                        resetPOS();
                    });
                } else {
                    alert('Error: ' + res.message);
                    $('#checkout-btn').prop('disabled', false).text('PROCESS PAYMENT');
                }
            },
            error: function() {
                alert('Server Error.');
                $('#checkout-btn').prop('disabled', false).text('PROCESS PAYMENT');
            }
        });
    }
    
    function resetPOS() {
        cart = [];
        renderCart();
        $('#selected-customer').text('Walk-In Customer');
        $('#customer-id').val('');
        $('#prescription-id').val('');
        $('#rx-info-panel').hide();
        $('#checkout-btn').prop('disabled', false).text('PROCESS PAYMENT');
        // Clear search fields
        $('#drug-search').val('');
        $('#patient-search-input').val('');
        // Reset product grid
        $('#product-grid').html('<div class="col-12 text-center text-muted p-4">Start typing to search for drugs...</div>');
    }
    
    // ==========================================
    // 1. KEYBOARD SHORTCUTS (HOTKEYS)
    // ==========================================

    // ==========================================
    // 2. HOLD & RESUME LOGIC (LocalStorage)
    // ==========================================
    
    // Check on load
    checkHeldSale();

    window.holdSale = function() {
        if (cart.length === 0) {
            alert('Cart is empty. Nothing to hold.');
            return;
        }

        const saleData = {
            cart: cart,
            customerName: $('#selected-customer').text(),
            customerId: $('#customer-id').val(),
            rxId: $('#prescription-id').val(),
            timestamp: new Date().toLocaleTimeString()
        };

        // Save to browser memory
        localStorage.setItem('held_pos_sale', JSON.stringify(saleData));
        
        // Clear current screen
        clearCart(); // This function already resets the UI
        
        // Update UI
        checkHeldSale();
        // Optional: toast notification
        // alert('Sale put on hold.'); 
    }

    window.resumeSale = function() {
        const heldData = localStorage.getItem('held_pos_sale');
        if (!heldData) return;

        // Protection: Don't overwrite current work without asking
        if (cart.length > 0) {
            if(!confirm('Current cart will be cleared to resume the held sale. Continue?')) {
                return;
            }
        }

        const data = JSON.parse(heldData);

        // Restore Data
        cart = data.cart;
        $('#selected-customer').text(data.customerName);
        $('#customer-id').val(data.customerId);
        $('#prescription-id').val(data.rxId);

        // Re-render
        renderCart();
        
        // Clear storage
        localStorage.removeItem('held_pos_sale');
        checkHeldSale();
    }

    function checkHeldSale() {
        const heldData = localStorage.getItem('held_pos_sale');
        if (heldData) {
            const data = JSON.parse(heldData);
            $('#held-sale-info').text(`Customer: ${data.customerName} (${data.timestamp})`);
            $('#held-sale-banner').slideDown();
        } else {
            $('#held-sale-banner').slideUp();
        }
    }
    
    window.clearCart = function() {
        cart = [];
        renderCart();
        $('#selected-customer').text('Walk-In Customer');
        $('#customer-id').val('');
        $('#prescription-id').val('');
        $('#rx-info-panel').hide();
    }

    </script>
</body>
</html>