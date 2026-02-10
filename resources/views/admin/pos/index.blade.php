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
        
        #qr-video {
            border-radius: 5px;
        }
        .scanning-line {
            position: relative;
            overflow: hidden;
        }
        .scanning-line::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: #4e73df;
            animation: scan 2s linear infinite;
        }
        @keyframes scan {
            0% { transform: translateY(0); }
            100% { transform: translateY(500px); }
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
            <!-- Replace the product search section with this enhanced version -->
            <div class="card">
                <div class="card-body">
                    <!-- Search Tabs -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="search-tab" data-toggle="tab" href="#search-panel" role="tab">
                                <i class="mdi mdi-magnify"></i> Search by Name/SKU
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="qr-tab" data-toggle="tab" href="#qr-panel" role="tab">
                                <i class="mdi mdi-qrcode-scan"></i> Scan QR Code
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Search by Name/SKU -->
                        <div class="tab-pane fade show active" id="search-panel" role="tabpanel">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" 
                                       id="product-search" 
                                       placeholder="Search products by name or SKU... (Press Enter to search)">
                            </div>
                        </div>

                        <!-- QR Code Scanner -->
                        <div class="tab-pane fade" id="qr-panel" role="tabpanel">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" 
                                       id="qr-code-input" 
                                       placeholder="Scan or enter QR code / SKU here..." 
                                       autofocus>
                                <small class="form-text text-muted">
                                    <i class="mdi mdi-information"></i> 
                                    Use a barcode/QR scanner or manually type the product SKU and press Enter
                                </small>
                            </div>

                            <!-- Alternative: Camera Scanner Button -->
                            <button type="button" class="btn btn-outline-primary btn-block" 
                                    onclick="startCameraScanner()" id="camera-scanner-btn">
                                <i class="mdi mdi-camera"></i> Use Camera Scanner
                            </button>

                            <!-- Video element for camera scanning (hidden by default) -->
                            <div id="camera-scanner" style="display: none;" class="mt-3">
                                <video id="qr-video" style="width: 100%; max-width: 500px; border: 2px solid #4e73df;"></video>
                                <button type="button" class="btn btn-danger btn-block mt-2" onclick="stopCameraScanner()">
                                    <i class="mdi mdi-close"></i> Stop Camera
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="product-grid" id="product-list">
                        <!-- Products will be displayed here -->
                        <div class="row">
                            @foreach($products as $product)
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="card product-card" onclick="addToCart({{ $product->id }})">
                                        <div class="card-body text-center p-2">
                                            @if($product->featured_image)
                                                <img src="{{ asset('storage/' . $product->featured_image) }}" 
                                                     alt="{{ $product->name }}" class="product-image mb-2" style="width:100%;">
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
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="mb-0">Payment Methods</label>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addPaymentMethod()">
                            <i class="mdi mdi-plus"></i> Add Payment
                        </button>
                    </div>

                    <div id="payment-methods">
                        <!-- Payment method rows will be added here -->
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
                                           placeholder="Amount" min="0" step="0.01" value="0">
                                </div>
                                <div class="col-1 p-0">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removePaymentMethod(this)" style="display:none;">
                                        <i class="mdi mdi-close"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-2" style="display:none;" id="payment-summary">
                        <small>
                            <strong>Total Paying:</strong> <span id="total-paying">₦0.00</span><br>
                            <strong>Balance:</strong> <span id="payment-balance">₦0.00</span>
                        </small>
                    </div>
                </div>

                <div class="mt-2">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="allow-partial" onchange="togglePartialPayment()">
                            Allow Partial Payment
                            <i class="input-helper"></i>
                        </label>
                    </div>
                </div>

                <div class="mt-2" id="change-display" style="display: none;">
                    <div class="alert alert-success">
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
    <script>
// Debug tab functionality
$(document).ready(function() {
    console.log('Bootstrap version:', $.fn.tooltip.Constructor.VERSION);
    
    // Manual tab switching if Bootstrap fails
    $('.nav-tabs a').click(function(e) {
        e.preventDefault();
        
        // Remove active from all
        $('.nav-tabs .nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');
        
        // Add active to clicked
        $(this).addClass('active');
        $($(this).attr('href')).addClass('show active');
        
        console.log('Tab switched to:', $(this).attr('href'));
    });
});
</script>
@endsection


@push('custom-scripts')
<!-- Ensure jQuery is loaded first (it should be from layout, but let's be explicit) -->
<script>
    // Wait for jQuery to be ready
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded!');
    }
</script>

<!-- POS Configuration - MUST load before pos.js -->
<script>
    // Define routes and CSRF token for POS JavaScript
    window.ROUTES = {
        searchProducts: '{{ route("admin.pos.search.products") }}',
        getProduct: '{{ url("admin/pos/product/:id") }}',
        searchQR: '{{ route("admin.pos.search.qr") }}',
        searchCustomer: '{{ route("admin.pos.search.customer") }}',
        createCustomer: '{{ route("admin.pos.create.customer") }}',
        processSale: '{{ route("admin.pos.process.sale") }}',
        receipt: '{{ url("admin/pos/receipt/:order") }}'
    };
    
    window.CSRF_TOKEN = '{{ csrf_token() }}';
    
    console.log('Routes loaded:', window.ROUTES);
</script>

<!-- QR Code Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<!-- POS Main Script - Load LAST -->
<script src="{{ asset('admin/js/pos.js') }}?v={{ time() }}"></script>

<script>
    // Verify everything loaded
    console.log('jQuery version:', $.fn.jquery);
    console.log('ROUTES available:', typeof ROUTES !== 'undefined');
    console.log('CSRF_TOKEN available:', typeof CSRF_TOKEN !== 'undefined');
</script>
@endpush