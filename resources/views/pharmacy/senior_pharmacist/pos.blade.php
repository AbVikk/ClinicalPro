<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Clinical Pro || Senior Pharmacist POS</title>

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
        background: #6f42c1; color: #fff; z-index: 1000;
        display: flex; align-items: center; justify-content: space-between; padding: 0 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .brand-logo { font-size: 20px; font-weight: 700; letter-spacing: 1px; }
    .user-profile { font-size: 14px; }
    
    /* Back Button */
    .back-btn {
        background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px;
        text-decoration: none; color: white; font-size: 14px;
        transition: 0.2s; display: flex; align-items: center;
    }
    .back-btn:hover { background: rgba(255,255,255,0.3); }
    
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
    .drug-card:hover { border-color: #6f42c1; box-shadow: 0 5px 15px rgba(111,66,193,0.1); }
    .drug-name { font-weight: 600; font-size: 14px; color: #333; line-height: 1.3; margin-bottom: 5px; }
    .drug-stock { font-size: 11px; color: #888; }
    .drug-price { font-weight: 700; color: #6f42c1; font-size: 16px; margin-top: auto; }
    .badge-stock { position: absolute; top: 10px; right: 10px; font-size: 10px; padding: 3px 6px; border-radius: 4px; background: #e6dcf7; color: #6f42c1; }
    
    /* RIGHT: Cart Area */
    .right-panel { flex: 35%; background: #fff; border-left: 1px solid #e0e0e0; display: flex; flex-direction: column; box-shadow: -5px 0 20px rgba(0,0,0,0.05); }
    
    .cart-header { padding: 20px; border-bottom: 1px solid #f0f0f0; background: #fafafa; }
    .patient-selector { 
        background: #e6dcf7; color: #6f42c1; padding: 10px 15px; border-radius: 8px; 
        cursor: pointer; display: flex; align-items: center; justify-content: space-between;
        font-weight: 600; transition: 0.2s;
    }
    .patient-selector:hover { background: #d4c4eb; }
    
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
</style>
</head>
<body>
    <!-- Top Navigation -->
    <div class="pos-topbar">
        <a href="{{ route('senior_pharmacist.dashboard') }}" class="back-btn">
            <i class="zmdi zmdi-arrow-back"></i> Back to Dashboard
        </a>
        <div class="brand-logo">CLINICAL PRO - SENIOR PHARMACIST POS</div>
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
                    <input type="text" id="drug-search" class="search-input" placeholder="Search drugs...">
                </div>
                
                <!-- Patient Search -->
                <div class="search-card" onclick="$('#patientModal').modal('show'); document.getElementById('patient-search-input').focus()">
                    <i class="zmdi zmdi-account"></i>
                    <input type="text" readonly class="search-input" placeholder="Select patient...">
                </div>
            </div>
            
            <!-- Product Grid -->
            <div id="product-grid" class="product-grid">
                <!-- Drugs will be populated here dynamically -->
            </div>
        </div>
        
        <!-- RIGHT PANEL: CART -->
        <div class="right-panel">
            <div class="cart-header">
                <div class="patient-selector" onclick="$('#patientModal').modal('show')">
                    <span id="selected-customer">Select Customer</span>
                    <i class="zmdi zmdi-chevron-down"></i>
                </div>
            </div>
            
            <div class="cart-body">
                <div id="cart-container">
                    <!-- Cart items will be populated here -->
                    <div class="text-center text-muted mt-5 pt-5">
                        <i class="zmdi zmdi-shopping-basket" style="font-size: 40px; opacity: 0.3;"></i>
                        <p class="mt-3">Cart is empty</p>
                    </div>
                </div>
            </div>
            
            <div class="cart-footer">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="subtotal-display">₦0.00</span>
                </div>
                <div class="total-row">
                    <span>TOTAL</span>
                    <span id="total-display">₦0.00</span>
                </div>
                <input type="hidden" id="customer-id">
                <input type="hidden" id="prescription-id">
                <button id="checkout-btn" class="checkout-btn" disabled onclick="processSale()">
                    <i class="zmdi zmdi-money"></i> PAY NOW
                </button>
            </div>
        </div>
    </div>
    
    <!-- Patient Selection Modal -->
    <div class="modal fade" id="patientModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Select Patient</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" id="patient-search-input" class="form-control" placeholder="Search patients with prescriptions...">
                    </div>
                    <ul id="patient-results-list" class="search-results-list">
                        <!-- Patient results will be populated here -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> 
    <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
    
    <script>
    $(document).ready(function() {
        // --- POS JAVASCRIPT LOGIC ---
        let cart = [];
        const cartContainer = document.getElementById('cart-container');
        const totalDisplay = document.getElementById('total-display');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const checkoutBtn = document.getElementById('checkout-btn');
        
        // 1. ADD TO CART
        window.addToCart = function(id, name, price, maxStock) {
            let existing = cart.find(i => i.id === id);
            if(existing) {
                if(existing.qty < maxStock) existing.qty++;
                else alert('Max stock reached for this item.');
            } else {
                cart.push({ id, name, price, qty: 1, max: maxStock });
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
                console.log('Rendering item:', item); // Debug log
                total += item.price * item.qty;
                let itemHtml = `
                <div class="cart-item">
                    <div class="item-info">
                        <div class="item-name">${item.name}</div>
                        <div class="item-meta">₦${formatMoney(item.price)} × ${item.qty}</div>
                    </div>
                    <div class="item-controls">
                        <div class="qty-btn" onclick="updateQty(${index}, -1)">-</div>
                        <div class="qty-val">${item.qty}</div>
                        <div class="qty-btn" onclick="updateQty(${index}, 1)">+</div>
                    </div>
                    <div class="item-price">₦${formatMoney(item.price * item.qty)}</div>
                    <div class="remove-btn ml-3" onclick="removeItem(${index})"><i class="zmdi zmdi-close"></i></div>
                </div>`;
                cartContainer.innerHTML += itemHtml;
            });

            totalDisplay.innerText = '₦' + formatMoney(total);
            subtotalDisplay.innerText = '₦' + formatMoney(total);
            checkoutBtn.disabled = false;
            checkoutBtn.innerHTML = `<i class="zmdi zmdi-money"></i> PAY ₦${formatMoney(total)}`;
        }
        
        // 3. UTILITIES
        window.updateQty = function(index, change) {
            let item = cart[index];
            let newQty = item.qty + change;
            if(newQty > 0 && newQty <= item.max) {
                item.qty = newQty;
            } else if (newQty > item.max) {
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
        
        // 4. LIVE DRUG SEARCH WITH DEBOUNCING
        const drugSearchInput = document.getElementById('drug-search');
        let drugSearchTimeout;
        
        if (drugSearchInput) {
            drugSearchInput.addEventListener('input', function() {
                clearTimeout(drugSearchTimeout);
                const query = this.value.trim();
                
                // Clear results if query is too short
                if(query.length < 2) {
                    const grid = document.getElementById('product-grid');
                    if (grid) grid.innerHTML = '';
                    return;
                }
                
                // Debounce the search request
                drugSearchTimeout = setTimeout(() => {
                    fetch(`{{ route('senior_pharmacist.sales.search-drugs') }}?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            const grid = document.getElementById('product-grid');
                            if (!grid) return;
                            
                            grid.innerHTML = '';
                            
                            if(data.length === 0) {
                                grid.innerHTML = `<div class="text-center text-muted py-5">No drugs found matching "${query}"</div>`;
                                return;
                            }
                            
                            data.forEach(item => {
                                const drugCard = document.createElement('div');
                                drugCard.className = 'drug-card';
                                drugCard.onclick = () => addToCart(item.id, item.name, item.price, item.stock);
                                drugCard.innerHTML = `
                                    <div style="position:relative;">
                                        <div class="drug-name">${item.name}</div>
                                        <span class="badge-stock">${item.stock} in stock</span>
                                    </div>
                                    <div class="drug-stock">SKU: ${item.id}</div>
                                    <div class="drug-price">₦${formatMoney(item.price)}</div>
                                    ${item.category ? `<div class="drug-category"><small class="text-muted">${item.category}${item.strength ? ' (' + item.strength + ')' : ''}</small></div>` : ''}
                                `;
                                grid.appendChild(drugCard);
                            });
                        })
                        .catch(error => {
                            console.error('Drug search error:', error);
                            const grid = document.getElementById('product-grid');
                            if (grid) {
                                grid.innerHTML = `<div class="text-center text-danger py-5">Error searching for drugs. Please try again.</div>`;
                            }
                        });
                }, 300); // 300ms debounce
            });
        }
        
        // 5. LIVE PATIENT SEARCH WITH DEBOUNCING
        const patientSearchInput = document.getElementById('patient-search-input');
        const patientResultsList = document.getElementById('patient-results-list');
        let patientSearchTimeout;
        
        if (patientSearchInput && patientResultsList) {
            patientSearchInput.addEventListener('input', function() {
                clearTimeout(patientSearchTimeout);
                const query = this.value.trim();
                
                // Clear results if query is empty
                if(query.length === 0) {
                    // Show default pending prescriptions
                    showDefaultPrescriptions();
                    return;
                }
                
                // Debounce the search request
                patientSearchTimeout = setTimeout(() => {
                    fetch(`{{ route('senior_pharmacist.sales.search-patient') }}?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            patientResultsList.innerHTML = '';
                            
                            if(data.length === 0) {
                                patientResultsList.innerHTML = `<li class="result-item text-center text-muted py-3">No patients with prescriptions found matching "${query}"</li>`;
                                return;
                            }
                            
                            data.forEach(patient => {
                                // Only show patients who have prescriptions
                                if(patient.prescriptions && patient.prescriptions.length > 0) {
                                    const patientItem = document.createElement('li');
                                    patientItem.className = 'result-item';
                                    
                                    // Calculate total for all prescription items
                                    let prescriptionTotal = 0;
                                    patient.prescriptions.forEach(prescription => {
                                        if(prescription.items) {
                                            prescription.items.forEach(item => {
                                                prescriptionTotal += (item.price || 0) * (item.quantity || 0);
                                            });
                                        }
                                    });
                                    
                                    patientItem.innerHTML = `
                                        <div class="d-flex justify-content-between">
                                            <strong>${patient.name}</strong>
                                            <span class="badge badge-info">₦${formatMoney(prescriptionTotal)}</span>
                                        </div>
                                        <small class="text-muted">${patient.phone || patient.email || 'No contact info'}</small>
                                        <div class="mt-2">
                                            ${patient.prescriptions.slice(0, 3).map(p => {
                                                // Calculate prescription total
                                                let rxTotal = 0;
                                                if(p.items) {
                                                    p.items.forEach(item => {
                                                        rxTotal += (item.price || 0) * (item.quantity || 0);
                                                    });
                                                }
                                                return `
                                                    <div class="rx-item mb-1 p-2 border rounded">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="badge badge-light">Rx #${p.id}</span>
                                                            <span class="badge badge-success">₦${formatMoney(rxTotal)}</span>
                                                        </div>
                                                        <div class="small mt-1">
                                                            ${p.items ? p.items.slice(0, 2).map(item => 
                                                                `<span class="badge badge-secondary mr-1">${item.name || item.medication_name}</span>`
                                                            ).join('') : ''}
                                                            ${p.items && p.items.length > 2 ? `<span class="badge badge-secondary">+${p.items.length - 2} more</span>` : ''}
                                                        </div>
                                                        <button class="btn btn-sm btn-primary mt-1" onclick="event.stopPropagation(); loadPrescription(${p.id}, '${patient.name.replace(/'/g, "\\'")}');">
                                                            Load Prescription
                                                        </button>
                                                    </div>
                                                `;
                                            }).join('')}
                                        </div>
                                    `;
                                    patientItem.onclick = (e) => {
                                        // Only select patient if not clicking on a prescription button
                                        if(!e.target.closest('button')) {
                                            $('#patientModal').modal('hide');
                                            document.getElementById('selected-customer').innerText = patient.name;
                                            document.getElementById('customer-id').value = patient.id;
                                        }
                                    };
                                    
                                    patientResultsList.appendChild(patientItem);
                                }
                            });
                        })
                        .catch(error => {
                            console.error('Patient search error:', error);
                            patientResultsList.innerHTML = `<li class="result-item text-center text-danger py-3">Error searching for patients. Please try again.</li>`;
                        });
                }, 300); // 300ms debounce
            });
        }
        
        // Show default pending prescriptions
        function showDefaultPrescriptions() {
            // This would refresh the default list from the server or restore the original list
            // For now, we'll just clear and let the modal reload if reopened
            if (patientResultsList) {
                patientResultsList.innerHTML = '';
            }
            // The default prescriptions will be shown when modal is reopened
        }
        
        // 6. PATIENT / PRESCRIPTION IMPORT
        window.loadPrescription = function(id, name) {
            $('#patientModal').modal('hide');
            document.getElementById('selected-customer').innerText = name;
            document.getElementById('prescription-id').value = id;

            // Fetch prescription items
            fetch(`{{ route('senior_pharmacist.sales.load-prescription', ['prescription' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', id))
                .then(res => res.json())
                .then(data => {
                    console.log('Prescription data:', data); // Debug log
                    if(data.status === 'success') {
                        cart = []; // Clear existing
                        let prescriptionTotal = 0;
                        
                        data.items.forEach(i => {
                            console.log('Item data:', i); // Debug log
                            // Check if stock exists
                            if(i.stock_check > 0) {
                                const quantity = Math.min(i.quantity, i.stock_check); // Don't exceed stock
                                cart.push({ 
                                    id: i.id, 
                                    name: i.name, 
                                    price: i.price, 
                                    qty: quantity, 
                                    max: i.stock_check 
                                });
                                prescriptionTotal += i.price * quantity;
                            } else {
                                // Add item but mark as out of stock
                                cart.push({ 
                                    id: i.id, 
                                    name: i.name + ' (Out of Stock)', 
                                    price: i.price, 
                                    qty: 0, 
                                    max: 0 
                                });
                            }
                        });
                        
                        renderCart();
                        
                        // Show prescription total
                        alert(`Prescription loaded successfully. Total: ₦${formatMoney(prescriptionTotal)}`);
                    } else {
                        alert(data.message || 'Error loading prescription');
                    }
                })
                .catch(error => {
                    console.error('Load prescription error:', error);
                    alert('Error loading prescription. Please try again.');
                });
        }
        
        // 7. PROCESS SALE
        window.processSale = function() {
            if(cart.length === 0) {
                alert('Cart is empty. Please add items before processing sale.');
                return;
            }
            
            let total = totalDisplay.innerText.replace('₦', '').replace(/,/g, '');
            let payload = {
                patient_id: document.getElementById('customer-id').value,
                prescription_id: document.getElementById('prescription-id').value,
                items: cart,
                total: total,
                _token: "{{ csrf_token() }}"
            };
            
            // Disable button during processing
            checkoutBtn.disabled = true;
            checkoutBtn.innerHTML = '<i class="zmdi zmdi-spinner zmdi-spin"></i> Processing...';
            
            // Send AJAX request
            fetch('{{ route('senior_pharmacist.sales.process-sale') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('Sale processed successfully! Order ID: ' + data.order_id);
                    // Reset cart
                    cart = [];
                    renderCart();
                    // Reset customer selection
                    document.getElementById('selected-customer').innerText = 'Select Customer';
                    document.getElementById('customer-id').value = '';
                    document.getElementById('prescription-id').value = '';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error processing sale. Please try again.');
            })
            .finally(() => {
                // Re-enable button
                checkoutBtn.disabled = false;
                checkoutBtn.innerHTML = '<i class="zmdi zmdi-money"></i> PAY NOW';
            });
        }
        
        // Clear search when modal is closed
        $('#patientModal').on('hidden.bs.modal', function () {
            if (patientSearchInput) {
                patientSearchInput.value = '';
            }
            showDefaultPrescriptions();
        });
    });
    </script>
</body>
</html>