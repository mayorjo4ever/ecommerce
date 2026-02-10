/**
 * POS System JavaScript
 * Handles product search, cart management, QR scanning, and checkout
 */

// Global Variables
let cart = [];
let selectedCustomer = null;
let html5QrCode = null;

// ==================== INITIALIZATION ====================
$(document).ready(function() {
    initializeEventListeners();
    updateCart();
    // alert('jquery working');
});

function initializeEventListeners() {
    // Product search by name/SKU
    $('#product-search').on('keypress', handleProductSearchKeypress);
    $('#product-search').on('keyup', handleProductSearchKeyup);
    
    // QR Code / SKU input
    $('#qr-code-input').on('keypress', handleQRCodeInput);
    
    // Customer search
    $('#customer-search').on('keyup', handleCustomerSearch);
    
    // Tab switching
    $('a[data-toggle="tab"]').on('shown.bs.tab', handleTabSwitch);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', handleKeyboardShortcuts);
    
    // Amount paid change
    $('#amount-paid').on('change keyup', calculateChange);
    
    // Discount and tax change
    $('#discount, #tax').on('change keyup', updateTotals);
}

// ==================== PRODUCT SEARCH ====================
function handleProductSearchKeypress(e) {
    if (e.which === 13) { // Enter key
        e.preventDefault();
        let search = $(this).val();
        
        if (search.length > 0) {
            searchProducts(search);
        }
    }
}

function handleProductSearchKeyup(e) {
    if (e.which === 13) return; // Skip if Enter
    
    let search = $(this).val();
    
    if (search.length > 2) {
        searchProducts(search);
    }
}

function searchProducts(search) {
    $.get(ROUTES.searchProducts, { search: search }, function(products) {
        displayProducts(products);
    }).fail(function() {
        showNotification('Error searching products', 'error');
    });
}

function displayProducts(products) {
    let html = '<div class="row">';
    
    products.forEach(product => {
        let image = product.featured_image 
            ? `<img src="/storage/${product.featured_image}" class="product-image mb-2" alt="${product.name}" style="width:100%">` 
            : `<div class="product-image bg-light d-flex align-items-center justify-content-center mb-2">
                 <i class="mdi mdi-image mdi-48px text-muted"></i>
               </div>`;
        
        let price = parseFloat(product.sale_price || product.price);
        
        html += `
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card product-card" onclick="addToCart(${product.id})">
                    <div class="card-body text-center p-2">
                        ${image}
                        <h6 class="mb-1">${product.name.substring(0, 30)}</h6>
                        <p class="text-muted small mb-1">${product.sku}</p>
                        <p class="text-success font-weight-bold mb-0">₦${price.toLocaleString('en-NG', {minimumFractionDigits: 2})}</p>
                        <small class="text-muted">Stock: ${product.quantity}</small>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    $('#product-list').html(html);
}

// ==================== QR CODE SCANNING ====================
function handleQRCodeInput(e) {
    if (e.which === 13) { // Enter key
        e.preventDefault();
        let code = $(this).val().trim();
        
        if (code.length > 0) {
            searchAndAddByQRCode(code);
            $(this).val(''); // Clear input for next scan
        }
    }
}

function searchAndAddByQRCode(code) {
    $.ajax({
        url: ROUTES.searchQR,
        method: 'POST',
        data: {
            qr_code: code,
            _token: CSRF_TOKEN
        },
        success: function(product) {
            showNotification(`Added: ${product.name}`, 'success');
            addProductToCart(product);
        },
        error: function(xhr) {
            let errorMsg = xhr.responseJSON?.error || 'Product not found';
            showNotification(errorMsg, 'error');
            
            // Fallback to regular search
            searchProducts(code);
        }
    });
}

// Camera Scanner Functions
function startCameraScanner() {
    $('#camera-scanner').show();
    $('#camera-scanner-btn').hide();
    
    html5QrCode = new Html5Qrcode("qr-video");
    
    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
            const cameraId = cameras[0].id;
            
            html5QrCode.start(
                cameraId,
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText) => {
                    searchAndAddByQRCode(decodedText);
                },
                (errorMessage) => {
                    // Parse error, ignore
                }
            ).catch(err => {
                console.error('Error starting camera:', err);
                showNotification('Failed to start camera. Please check permissions.', 'error');
            });
        }
    }).catch(err => {
        console.error('Error getting cameras:', err);
        showNotification('No camera found or permission denied.', 'error');
    });
}

function stopCameraScanner() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            $('#camera-scanner').hide();
            $('#camera-scanner-btn').show();
        });
    }
}

// ==================== CART MANAGEMENT ====================
function addToCart(productId) {
    $.get(ROUTES.getProduct.replace(':id', productId), function(product) {
        addProductToCart(product);
    }).fail(function() {
        showNotification('Error loading product', 'error');
    });
}

function addProductToCart(product) {
    let existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
        if (existingItem.quantity < product.quantity) {
            existingItem.quantity++;
        } else {
            showNotification('Insufficient stock!', 'warning');
            return;
        }
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            sku: product.sku,
            price: parseFloat(product.price),
            quantity: 1,
            stock: product.quantity
        });
    }
    
    updateCart();
}

function updateCart() {
    if (cart.length === 0) {
        $('#cart-items').html('<p class="text-center text-muted">Cart is empty</p>');
        updateTotals();
        return;
    }

    let html = '';
    cart.forEach((item, index) => {
        html += `
            <div class="cart-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div style="flex: 1;">
                        <strong>${item.name}</strong><br>
                        <small class="text-muted">${item.sku}</small><br>
                        <small>₦${item.price.toLocaleString('en-NG', {minimumFractionDigits: 2})} × ${item.quantity}</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-secondary" onclick="decreaseQuantity(${index})">
                            <i class="mdi mdi-minus"></i>
                        </button>
                        <span class="mx-2">${item.quantity}</span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="increaseQuantity(${index})">
                            <i class="mdi mdi-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-danger ml-2" onclick="removeFromCart(${index})">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
                <div class="text-right mt-2">
                    <strong>₦${(item.price * item.quantity).toLocaleString('en-NG', {minimumFractionDigits: 2})}</strong>
                </div>
            </div>
        `;
    });
    
    $('#cart-items').html(html);
    updateTotals();
}

function increaseQuantity(index) {
    if (cart[index].quantity < cart[index].stock) {
        cart[index].quantity++;
        updateCart();
    } else {
        showNotification('Insufficient stock!', 'warning');
    }
}

function decreaseQuantity(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity--;
        updateCart();
    }
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

function clearCart() {
    if (confirm('Clear all items from cart?')) {
        cart = [];
        updateCart();
    }
}

function updateTotals() {
    let subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let discount = parseFloat($('#discount').val()) || 0;
    let tax = parseFloat($('#tax').val()) || 0;
    let total = subtotal - discount + tax;

    $('#subtotal').text('₦' + subtotal.toLocaleString('en-NG', {minimumFractionDigits: 2}));
    $('#total').text('₦' + total.toLocaleString('en-NG', {minimumFractionDigits: 2}));
    $('#amount-paid').val(total.toFixed(2));
    calculateChange();
}

function calculateChange() {
    let total = parseFloat($('#total').text().replace('₦', '').replace(/,/g, ''));
    let paid = parseFloat($('#amount-paid').val()) || 0;
    let change = paid - total;

    if (change >= 0) {
        $('#change-amount').text('₦' + change.toLocaleString('en-NG', {minimumFractionDigits: 2}));
        $('#change-display').show();
    } else {
        $('#change-display').hide();
    }
}

// ==================== CUSTOMER MANAGEMENT ====================
let searchTimeout;

function handleCustomerSearch() {
    clearTimeout(searchTimeout);
    let search = $(this).val();
    
    if (search.length > 2) {
        searchTimeout = setTimeout(function() {
            $.get(ROUTES.searchCustomer, { search: search }, function(customers) {
                displayCustomers(customers);
            });
        }, 300);
    } else {
        $('#customer-results').hide();
    }
}

function displayCustomers(customers) {
    if (customers.length === 0) {
        $('#customer-results').hide();
        return;
    }

    let html = '';
    customers.forEach(customer => {
        html += `
            <a href="#" class="list-group-item list-group-item-action" 
               onclick="selectCustomer(${customer.id}, '${customer.name}', '${customer.email}'); return false;">
                <strong>${customer.name}</strong><br>
                <small>${customer.email}</small>
            </a>
        `;
    });
    
    $('#customer-results').html(html).show();
}

function selectCustomer(id, name, email) {
    selectedCustomer = id;
    $('#selected-customer-id').val(id);
    $('#customer-name').text(name);
    $('#customer-email').text(email);
    $('#selected-customer').show();
    $('#customer-results').hide();
    $('#customer-search').val('');
}

function createQuickCustomer() {
    let data = {
        name: $('#quick-name').val(),
        phone: $('#quick-phone').val(),
        email: $('#quick-email').val(),
        _token: CSRF_TOKEN
    };

    $.post(ROUTES.createCustomer, data, function(customer) {
        selectCustomer(customer.id, customer.name, customer.email);
        $('#createCustomerModal').modal('hide');
        $('#quick-customer-form')[0].reset();
        showNotification('Customer created successfully!', 'success');
    }).fail(function(error) {
        let errorMsg = error.responseJSON?.message || 'Error creating customer';
        showNotification(errorMsg, 'error');
    });
}

// ==================== CHECKOUT ====================
function completeSale() {
    if (cart.length === 0) {
        showNotification('Cart is empty!', 'warning');
        return;
    }

    if (!selectedCustomer) {
        showNotification('Please select a customer!', 'warning');
        return;
    }

    let total = parseFloat($('#total').text().replace('₦', '').replace(/,/g, ''));
    let amountPaid = parseFloat($('#amount-paid').val()) || 0;

    if (amountPaid < total) {
        showNotification('Insufficient payment amount!', 'warning');
        return;
    }

    let data = {
        customer_id: selectedCustomer,
        items: cart.map(item => ({
            product_id: item.id,
            quantity: item.quantity,
            price: item.price
        })),
        payment_method: $('#payment-method').val(),
        amount_paid: amountPaid,
        discount: parseFloat($('#discount').val()) || 0,
        tax: parseFloat($('#tax').val()) || 0,
        _token: CSRF_TOKEN
    };

    $.post(ROUTES.processSale, data, function(response) {
        showNotification('Sale completed successfully!', 'success');
        
        if (confirm('Sale completed!\nChange: ₦' + response.change.toLocaleString('en-NG', {minimumFractionDigits: 2}) + '\n\nDo you want to print the receipt?')) {
            window.open(ROUTES.receipt.replace(':order', response.order.id), '_blank');
        }
        
        // Reset
        resetPOS();
    }).fail(function(error) {
        let errorMsg = error.responseJSON?.error || 'Failed to process sale';
        showNotification('Error: ' + errorMsg, 'error');
    });
}

function resetPOS() {
    cart = [];
    selectedCustomer = null;
    $('#selected-customer').hide();
    $('#discount').val(0);
    $('#tax').val(0);
    $('#customer-search').val('');
    updateCart();
    location.reload();
}

// ==================== UTILITIES ====================
function showNotification(message, type = 'success') {
    let bgColor = type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#ffc107';
    let icon = type === 'success' ? 'mdi-check-circle' : type === 'error' ? 'mdi-alert-circle' : 'mdi-information';
    
    let notification = $(`
        <div class="pos-notification" style="position: fixed; top: 70px; right: 20px; z-index: 9999; 
                    background: ${bgColor}; color: white; padding: 15px 25px; 
                    border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    animation: slideIn 0.3s ease-out;">
            <i class="mdi ${icon}"></i> ${message}
        </div>
    `);
    
    $('body').append(notification);
    
    setTimeout(function() {
        notification.fadeOut(300, function() {
            $(this).remove();
        });
    }, 3000);
}

function handleTabSwitch(e) {
    if (e.target.id === 'qr-tab') {
        setTimeout(function() {
            $('#qr-code-input').focus();
        }, 100);
    } else if (e.target.id === 'search-tab') {
        setTimeout(function() {
            $('#product-search').focus();
        }, 100);
    }
}

function handleKeyboardShortcuts(e) {
    // F9 - Complete Sale
    if (e.key === 'F9') {
        e.preventDefault();
        completeSale();
    }
    
    // ESC - Clear Cart
    if (e.key === 'Escape') {
        e.preventDefault();
        clearCart();
    }
    
    // F2 - Focus on product search
    if (e.key === 'F2') {
        e.preventDefault();
        $('#search-tab').tab('show');
        $('#product-search').focus();
    }
    
    // F3 - Focus on QR scanner
    if (e.key === 'F3') {
        e.preventDefault();
        $('#qr-tab').tab('show');
        $('#qr-code-input').focus();
    }
    
    // F4 - Focus on customer search
    if (e.key === 'F4') {
        e.preventDefault();
        $('#customer-search').focus();
    }
}

// ==================== ANIMATION STYLES ====================
if (!$('#pos-animations').length) {
    $('head').append(`
        <style id="pos-animations">
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        </style>
    `);
}



// ==================== PAYMENT MANAGEMENT ====================
let paymentMethods = [];
let allowPartialPayment = false;

window.addPaymentMethod = function() {
    const paymentRow = `
        <div class="payment-method-row mb-2">
            <div class="row">
                <div class="col-7">
                    <select class="form-control form-control-sm payment-method">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="pos">POS</option>
                        <option value="transfer">Bank Transfer</option>
                    </select>
                </div>
                <div class="col-4">
                    <input type="number" class="form-control form-control-sm payment-amount" 
                           placeholder="Amount" min="0" step="0.01" value="0" onchange="calculatePaymentTotals()">
                </div>
                <div class="col-1 p-0">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removePaymentMethod(this)">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    $('#payment-methods').append(paymentRow);
    calculatePaymentTotals();
};

window.removePaymentMethod = function(button) {
    $(button).closest('.payment-method-row').remove();
    calculatePaymentTotals();
};

window.togglePartialPayment = function() {
    allowPartialPayment = $('#allow-partial').is(':checked');
    calculatePaymentTotals();
};

function calculatePaymentTotals() {
    let totalPaying = 0;
    
    $('.payment-amount').each(function() {
        totalPaying += parseFloat($(this).val()) || 0;
    });
    
    let orderTotal = parseFloat($('#total').text().replace('₦', '').replace(/,/g, ''));
    let balance = orderTotal - totalPaying;
    let change = totalPaying - orderTotal;
    
    $('#total-paying').text('₦' + totalPaying.toLocaleString('en-NG', {minimumFractionDigits: 2}));
    $('#payment-balance').text('₦' + Math.abs(balance).toLocaleString('en-NG', {minimumFractionDigits: 2}));
    $('#payment-summary').show();
    
    if (change > 0) {
        $('#change-amount').text('₦' + change.toLocaleString('en-NG', {minimumFractionDigits: 2}));
        $('#change-display').show();
    } else {
        $('#change-display').hide();
    }
}

// Update the completeSale function
window.completeSale = function() {
    if (cart.length === 0) {
        showNotification('Cart is empty!', 'warning');
        return;
    }

    if (!selectedCustomer) {
        showNotification('Please select a customer!', 'warning');
        return;
    }

    // Collect payment methods
    let payments = [];
    $('.payment-method-row').each(function() {
        let method = $(this).find('.payment-method').val();
        let amount = parseFloat($(this).find('.payment-amount').val()) || 0;
        
        if (amount > 0) {
            payments.push({ method: method, amount: amount });
        }
    });

    if (payments.length === 0) {
        showNotification('Please add at least one payment method!', 'warning');
        return;
    }

    let total = parseFloat($('#total').text().replace('₦', '').replace(/,/g, ''));
    let totalPaying = payments.reduce((sum, p) => sum + p.amount, 0);

    // Check if payment is sufficient or partial payment is allowed
    if (totalPaying < total && !allowPartialPayment) {
        showNotification('Insufficient payment amount! Enable "Allow Partial Payment" or add more payment.', 'warning');
        return;
    }

    let data = {
        customer_id: selectedCustomer,
        items: cart.map(item => ({
            product_id: item.id,
            quantity: item.quantity,
            price: item.price
        })),
        payments: payments,
        discount: parseFloat($('#discount').val()) || 0,
        tax: parseFloat($('#tax').val()) || 0,
        _token: CSRF_TOKEN
    };

    $.post(ROUTES.processSale, data, function(response) {
        let message = response.payment_status === 'paid' 
            ? 'Sale completed successfully!'
            : `Partial payment recorded!\nBalance: ₦${response.balance.toLocaleString('en-NG', {minimumFractionDigits: 2})}`;
        
        showNotification(message, 'success');
        
        let printMessage = response.payment_status === 'paid'
            ? `Sale completed!\nChange: ₦${response.change.toLocaleString('en-NG', {minimumFractionDigits: 2})}\n\nPrint receipt?`
            : `Partial payment recorded!\nBalance: ₦${response.balance.toLocaleString('en-NG', {minimumFractionDigits: 2})}\n\nPrint receipt?`;
        
        if (confirm(printMessage)) {
            window.open(ROUTES.receipt.replace(':order', response.order.id), '_blank');
        }
        
        // Reset
        resetPOS();
    }).fail(function(error) {
        let errorMsg = error.responseJSON?.error || 'Failed to process sale';
        showNotification('Error: ' + errorMsg, 'error');
    });
};

// Add event listener for payment amount changes
$(document).on('change keyup', '.payment-amount', function() {
    calculatePaymentTotals();
});