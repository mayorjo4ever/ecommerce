@extends('admin.layouts.app')

@section('title', 'Point of Sale')

@push('plugin-styles')
    <style>
        .pos-container {
            height: calc(100vh - 140px);
            overflow: hidden;
        }
        .product-grid {
            height: calc(100vh - 220px);
            overflow-y: auto;
        }
        .cart-section {
            height: calc(100vh - 220px);
            overflow-y: auto;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
            padding: 15px;
            background: #f8f9fc;
        }
        .product-card {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .cart-item {
            background: white;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #e3e6f0;
        }
        .total-section {
            background: #4e73df;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .product-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">
                    <i class="mdi mdi-point-of-sale"></i> Point of Sale
                </h4>
                <div>
                    <a href="{{ route('admin.pos.history') }}" class="btn btn-info">
                        <i class="mdi mdi-history"></i> Sales History
                    </a>
                    <button type="button" class="btn btn-warning" onclick="clearCart()">
                        <i class="mdi mdi-cart-remove"></i> Clear Cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row pos-container">
        <!-- Products Section -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-lg" 
                               id="product-search" placeholder="Search products by name or SKU...">
                    </div>

                    <div class="product-grid" id="product-list">
                        <div class="row">
                            @foreach($products as $product)
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="card product-card" onclick="addToCart({{ $product->id }})">
                                        <div class="card-body text-center p-2">
                                            @if($product->featured_image)
                                                <img src="{{ asset('storage/' . $product->featured_image) }}" 
                                                     alt="{{ $product->name }}" class="product-image mb-2">
                                            @else
                                                <div class="product-image bg-light d-flex align-items-center justify-content-center mb-2">
                                                    <i class="mdi mdi-image mdi-48px text-muted"></i>
                                                </div>
                                            @endif
                                            <h6 class="mb-1">{{ Str::limit($product->name, 30) }}</h6>
                                            <p class="text-muted small mb-1">{{ $product->sku }}</p>
                                            <p class="text-success font-weight-bold mb-0">
                                                ₦{{ number_format($product->getCurrentPrice(), 2) }}
                                            </p>
                                            <small class="text-muted">Stock: {{ $product->quantity }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="col-md-5">
            <div class="cart-section">
                <h5 class="mb-3">Shopping Cart</h5>
                
                <!-- Customer Selection -->
                <div class="form-group">
                    <label>Customer</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="customer-search" 
                               placeholder="Search customer...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" data-toggle="modal" 
                                    data-target="#createCustomerModal">
                                <i class="mdi mdi-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div id="customer-results" class="list-group mt-2" style="display: none;"></div>
                    <input type="hidden" id="selected-customer-id">
                    <div id="selected-customer" class="mt-2" style="display: none;">
                        <div class="alert alert-info">
                            <strong id="customer-name"></strong><br>
                            <small id="customer-email"></small>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Cart Items -->
                <div id="cart-items">
                    <p class="text-center text-muted">Cart is empty</p>
                </div>

                <!-- Totals -->
                <div class="total-section">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong id="subtotal">₦0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount:</span>
                        <input type="number" class="form-control form-control-sm d-inline-block" 
                               id="discount" value="0" min="0" step="0.01" 
                               style="width: 100px; background: white;" onchange="updateTotals()">
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <input type="number" class="form-control form-control-sm d-inline-block" 
                               id="tax" value="0" min="0" step="0.01" 
                               style="width: 100px; background: white;" onchange="updateTotals()">
                    </div>
                    <hr style="border-color: rgba(255,255,255,0.3);">
                    <div class="d-flex justify-content-between">
                        <h5>Total:</h5>
                        <h5 id="total">₦0.00</h5>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="mt-3">
                    <label>Payment Method</label>
                    <select class="form-control" id="payment-method">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="pos">POS</option>
                        <option value="transfer">Bank Transfer</option>
                    </select>
                </div>

                <div class="mt-3">
                    <label>Amount Paid</label>
                    <input type="number" class="form-control" id="amount-paid" 
                           value="0" min="0" step="0.01" onchange="calculateChange()">
                </div>

                <div class="mt-2">
                    <div class="alert alert-success" id="change-display" style="display: none;">
                        Change: <strong id="change-amount">₦0.00</strong>
                    </div>
                </div>

                <button class="btn btn-success btn-block btn-lg mt-3" onclick="completeSale()">
                    <i class="mdi mdi-cash-register"></i> Complete Sale
                </button>
            </div>
        </div>
    </div>

    <!-- Create Customer Modal -->
    <div class="modal fade" id="createCustomerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Quick Customer</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="quick-customer-form">
                        <div class="form-group">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="quick-name" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control" id="quick-phone">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" id="quick-email">
                            <small class="text-muted">Optional - auto-generated if empty</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="createQuickCustomer()">
                        Create Customer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    let cart = [];
    let selectedCustomer = null;

    // Product Search
    $('#product-search').on('keyup', function() {
        let search = $(this).val();
        
        if (search.length > 2) {
            $.get('{{ route("admin.pos.search.products") }}', { search: search }, function(products) {
                displayProducts(products);
            });
        }
    });

    function displayProducts(products) {
        let html = '<div class="row">';
        products.forEach(product => {
            let image = product.featured_image 
                ? `<img src="/storage/${product.featured_image}" class="product-image mb-2">` 
                : `<div class="product-image bg-light d-flex align-items-center justify-content-center mb-2">
                     <i class="mdi mdi-image mdi-48px text-muted"></i>
                   </div>`;
            
            html += `
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card product-card" onclick="addToCart(${product.id})">
                        <div class="card-body text-center p-2">
                            ${image}
                            <h6 class="mb-1">${product.name.substring(0, 30)}</h6>
                            <p class="text-muted small mb-1">${product.sku}</p>
                            <p class="text-success font-weight-bold mb-0">₦${parseFloat(product.sale_price || product.price).toLocaleString()}</p>
                            <small class="text-muted">Stock: ${product.quantity}</small>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        $('#product-list').html(html);
    }

    // Add to cart
    function addToCart(productId) {
        $.get(`{{ url('admin/pos/product') }}/${productId}`, function(product) {
            let existingItem = cart.find(item => item.id === product.id);
            
            if (existingItem) {
                if (existingItem.quantity < product.quantity) {
                    existingItem.quantity++;
                } else {
                    alert('Insufficient stock!');
                    return;
                }
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    sku: product.sku,
                    price: product.price,
                    quantity: 1,
                    stock: product.quantity
                });
            }
            
            updateCart();
        });
    }

    // Update cart display
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
                            <small>₦${parseFloat(item.price).toLocaleString()} × ${item.quantity}</small>
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
                        <strong>₦${(item.price * item.quantity).toLocaleString()}</strong>
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
            alert('Insufficient stock!');
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

        $('#subtotal').text('₦' + subtotal.toLocaleString());
        $('#total').text('₦' + total.toLocaleString());
        $('#amount-paid').val(total.toFixed(2));
        calculateChange();
    }

    function calculateChange() {
        let total = parseFloat($('#total').text().replace('₦', '').replace(/,/g, ''));
        let paid = parseFloat($('#amount-paid').val()) || 0;
        let change = paid - total;

        if (change >= 0) {
            $('#change-amount').text('₦' + change.toLocaleString());
            $('#change-display').show();
        } else {
            $('#change-display').hide();
        }
    }

    // Customer Search
    let searchTimeout;
    $('#customer-search').on('keyup', function() {
        clearTimeout(searchTimeout);
        let search = $(this).val();
        
        if (search.length > 2) {
            searchTimeout = setTimeout(function() {
                $.get('{{ route("admin.pos.search.customer") }}', { search: search }, function(customers) {
                    displayCustomers(customers);
                });
            }, 300);
        } else {
            $('#customer-results').hide();
        }
    });

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

    // Create Quick Customer
    function createQuickCustomer() {
        let data = {
            name: $('#quick-name').val(),
            phone: $('#quick-phone').val(),
            email: $('#quick-email').val(),
            _token: '{{ csrf_token() }}'
        };

        $.post('{{ route("admin.pos.create.customer") }}', data, function(customer) {
            selectCustomer(customer.id, customer.name, customer.email);
            $('#createCustomerModal').modal('hide');
            $('#quick-customer-form')[0].reset();
        }).fail(function(error) {
            alert('Error creating customer: ' + (error.responseJSON?.message || 'Unknown error'));
        });
    }

    // Complete Sale
    function completeSale() {
        if (cart.length === 0) {
            alert('Cart is empty!');
            return;
        }

        if (!selectedCustomer) {
            alert('Please select a customer!');
            return;
        }

        let total = parseFloat($('#total').text().replace('₦', '').replace(/,/g, ''));
        let amountPaid = parseFloat($('#amount-paid').val()) || 0;

        if (amountPaid < total) {
            alert('Insufficient payment amount!');
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
            _token: '{{ csrf_token() }}'
        };

        $.post('{{ route("admin.pos.process.sale") }}', data, function(response) {
            alert('Sale completed successfully!\nChange: ₦' + response.change.toLocaleString());
            
            // Ask if user wants to print receipt
            if (confirm('Do you want to print the receipt?')) {
                window.open('{{ url("admin/pos/receipt") }}/' + response.order.id, '_blank');
            }
            
            // Reset
            cart = [];
            selectedCustomer = null;
            $('#selected-customer').hide();
            $('#discount').val(0);
            $('#tax').val(0);
            updateCart();
            location.reload();
        }).fail(function(error) {
            alert('Error: ' + (error.responseJSON?.error || 'Failed to process sale'));
        });
    }
    
    // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
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
                $('#product-search').focus();
            }

            // F3 - Focus on customer search
            if (e.key === 'F3') {
                e.preventDefault();
                $('#customer-search').focus();
            }
        });

        // Show keyboard shortcuts help
        console.log(`
        POS Keyboard Shortcuts:
        - F2: Focus Product Search
        - F3: Focus Customer Search
        - F9: Complete Sale
        - ESC: Clear Cart
        `);
</script>
@endpush