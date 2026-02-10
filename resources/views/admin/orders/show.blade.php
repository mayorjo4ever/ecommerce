@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold mb-0">Order #{{ $order->order_number }}</h4>
                    <p class="text-muted mb-0">
                        Placed on {{ $order->created_at->format('d M, Y H:i') }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.pos.receipt', $order->id) }}" 
                       class="btn btn-info" target="_blank">
                        <i class="mdi mdi-printer"></i> Print Receipt
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-8">

            <!-- Order Items Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Order Items</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->featured_image)
                                                    <img src="{{ asset('storage/' . $item->product->featured_image) }}"
                                                         alt="{{ $item->product->name }}"
                                                         style="width:45px; height:45px; object-fit:cover; border-radius:5px;"
                                                         class="mr-3">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center mr-3"
                                                         style="width:45px; height:45px; border-radius:5px;">
                                                        <i class="mdi mdi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $item->product->name ?? 'Deleted Product' }}</strong><br>
                                                    <small class="text-muted">
                                                        {{ $item->product->category->name ?? 'N/A' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->product->sku ?? 'N/A' }}</td>
                                        <td>₦{{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-right">
                                            <strong>₦{{ number_format($item->total, 2) }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
                                    <td class="text-right">₦{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                @if($order->discount > 0)
                                <tr class="table-light">
                                    <td colspan="4" class="text-right"><strong>Discount:</strong></td>
                                    <td class="text-right text-danger">
                                        - ₦{{ number_format($order->discount, 2) }}
                                    </td>
                                </tr>
                                @endif
                                @if($order->tax > 0)
                                <tr class="table-light">
                                    <td colspan="4" class="text-right"><strong>Tax:</strong></td>
                                    <td class="text-right">₦{{ number_format($order->tax, 2) }}</td>
                                </tr>
                                @endif
                                @if($order->shipping > 0)
                                <tr class="table-light">
                                    <td colspan="4" class="text-right"><strong>Shipping:</strong></td>
                                    <td class="text-right">₦{{ number_format($order->shipping, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="table-primary">
                                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                    <td class="text-right">
                                        <strong>₦{{ number_format($order->total, 2) }}</strong>
                                    </td>
                                </tr>
                                <tr class="table-success">
                                    <td colspan="4" class="text-right"><strong>Amount Paid:</strong></td>
                                    <td class="text-right">
                                        <strong>₦{{ number_format($order->amount_paid, 2) }}</strong>
                                    </td>
                                </tr>
                                @if($order->balance > 0)
                                <tr class="table-warning">
                                    <td colspan="4" class="text-right"><strong>Balance:</strong></td>
                                    <td class="text-right">
                                        <strong>₦{{ number_format($order->balance, 2) }}</strong>
                                    </td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment History Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Payment History</h4>
                        @if($order->payment_status !== 'paid')
                            <button type="button" class="btn btn-success btn-sm"
                                    data-toggle="modal" data-target="#addPaymentModal">
                                <i class="mdi mdi-cash-plus"></i> Record Payment
                            </button>
                        @endif
                    </div>

                    @if($order->payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Processed By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->payments as $payment)
                                        <tr>
                                            <td>
                                                <small>{{ $payment->transaction_id }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info text-uppercase">
                                                    {{ $payment->payment_method }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>₦{{ number_format($payment->amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $payment->created_at->format('d M, Y H:i') }}</td>
                                            <td>
                                                @php
                                                    $details = is_array($payment->payment_details) 
                                                        ? $payment->payment_details 
                                                        : json_decode($payment->payment_details, true);
                                                @endphp
                                                {{ $details['processed_by'] ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-success">
                                        <td colspan="2"><strong>Total Paid:</strong></td>
                                        <td colspan="4">
                                            <strong>₦{{ number_format($order->amount_paid, 2) }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No payments recorded yet.</p>
                    @endif
                </div>
            </div>

            <!-- Notes Card -->
            @if($order->notes)
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Notes</h4>
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-md-4">

            <!-- Order Status Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Order Status</h4>

                    <div class="mb-3">
                        <strong>Order Status:</strong><br>
                        <span class="badge badge-{{ $order->status_color }} badge-lg">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Payment Status:</strong><br>
                        <span class="badge badge-{{ $order->payment_status_color }} badge-lg">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>

                    @if($order->balance > 0)
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert"></i>
                            <strong>Balance Due:</strong><br>
                            ₦{{ number_format($order->balance, 2) }}
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="mdi mdi-check-circle"></i>
                            Fully Paid
                        </div>
                    @endif

                    <!-- Update Order Status -->
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Update Status</label>
                            <select class="form-control" name="status">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="mdi mdi-update"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Customer Info Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Customer Information</h4>
                    <div class="mb-2">
                        <strong>Name:</strong><br>
                        {{ $order->user->name ?? 'N/A' }}
                    </div>
                    <div class="mb-2">
                        <strong>Email:</strong><br>
                        {{ $order->user->email ?? 'N/A' }}
                    </div>
                    <div class="mb-2">
                        <strong>Phone:</strong><br>
                        {{ $order->user->phone ?? 'N/A' }}
                    </div>
                    @if($order->user)
                        <a href="{{ route('admin.customers.show', $order->user->id) }}"
                           class="btn btn-sm btn-info btn-block mt-2">
                            <i class="mdi mdi-account"></i> View Customer
                        </a>
                    @endif
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Order Summary</h4>
                    <div class="mb-2">
                        <strong>Order #:</strong><br>
                        <small>{{ $order->order_number }}</small>
                    </div>
                    <div class="mb-2">
                        <strong>Items:</strong><br>
                        {{ $order->orderItems->sum('quantity') }} items
                    </div>
                    <div class="mb-2">
                        <strong>Created:</strong><br>
                        {{ $order->created_at->format('d M, Y H:i') }}
                    </div>
                    <div class="mb-2">
                        <strong>Last Updated:</strong><br>
                        {{ $order->updated_at->format('d M, Y H:i') }}
                    </div>
                </div>
            </div>

            <!-- QR Code Card -->
            @if($order->qr_code)
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h4 class="card-title">Order QR Code</h4>
                        <img src="{{ asset('storage/' . $order->qr_code) }}"
                             alt="Order QR Code" style="max-width: 150px;">
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Record Additional Payment</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Remaining Balance:</strong>
                        ₦{{ number_format($order->balance, 2) }}
                    </div>

                    <div id="additional-payment-methods">
                        <div class="payment-entry mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <label>Payment Method</label>
                                    <select class="form-control additional-payment-method">
                                        <option value="cash">Cash</option>
                                        <option value="card">Card</option>
                                        <option value="pos">POS</option>
                                        <option value="transfer">Bank Transfer</option>
                                    </select>
                                </div>
                                <div class="col-5">
                                    <label>Amount</label>
                                    <input type="number" class="form-control additional-payment-amount"
                                           placeholder="Amount" min="0" step="0.01"
                                           value="{{ $order->balance }}"
                                           max="{{ $order->balance }}">
                                </div>
                                <div class="col-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="removeAdditionalPayment(this)"
                                            style="display:none;">
                                        <i class="mdi mdi-close"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-primary"
                            onclick="addAdditionalPayment()">
                        <i class="mdi mdi-plus"></i> Add Another Method
                    </button>

                    <div class="mt-3 alert alert-light" id="additional-payment-summary">
                        <small>
                            <strong>Total Paying:</strong>
                            <span id="additional-total-paying">₦0.00</span><br>
                            <strong>Remaining Balance:</strong>
                            <span id="additional-remaining">₦{{ number_format($order->balance, 2) }}</span>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="submitAdditionalPayment()">
                        <i class="mdi mdi-cash-check"></i> Record Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    const ORDER_ID = {{ $order->id }};
    const ORDER_BALANCE = {{ $order->balance }};
    const RECORD_PAYMENT_URL = '{{ route("admin.pos.record.payment", $order->id) }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // Additional payment methods in modal
    function addAdditionalPayment() {
        let html = `
            <div class="payment-entry mb-3">
                <div class="row">
                    <div class="col-6">
                        <label>Payment Method</label>
                        <select class="form-control additional-payment-method">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="pos">POS</option>
                            <option value="transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="col-5">
                        <label>Amount</label>
                        <input type="number" class="form-control additional-payment-amount"
                               placeholder="Amount" min="0" step="0.01" value="0">
                    </div>
                    <div class="col-1 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-danger"
                                onclick="removeAdditionalPayment(this)">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#additional-payment-methods').append(html);
        updateAdditionalPaymentSummary();
    }

    function removeAdditionalPayment(button) {
        $(button).closest('.payment-entry').remove();
        updateAdditionalPaymentSummary();
    }

    function updateAdditionalPaymentSummary() {
        let totalPaying = 0;
        
        $('.additional-payment-amount').each(function() {
            totalPaying += parseFloat($(this).val()) || 0;
        });
        
        let remaining = ORDER_BALANCE - totalPaying;
        
        $('#additional-total-paying').text('₦' + totalPaying.toLocaleString('en-NG', {minimumFractionDigits: 2}));
        $('#additional-remaining').text('₦' + Math.max(0, remaining).toLocaleString('en-NG', {minimumFractionDigits: 2}));
    }

    function submitAdditionalPayment() {
        let payments = [];
        
        $('.payment-entry').each(function() {
            let method = $(this).find('.additional-payment-method').val();
            let amount = parseFloat($(this).find('.additional-payment-amount').val()) || 0;
            
            if (amount > 0) {
                payments.push({ method: method, amount: amount });
            }
        });

        if (payments.length === 0) {
            alert('Please enter a payment amount.');
            return;
        }

        let totalPaying = payments.reduce((sum, p) => sum + p.amount, 0);
        
        if (totalPaying > ORDER_BALANCE) {
            alert('Payment amount exceeds balance of ₦' + ORDER_BALANCE.toLocaleString('en-NG', {minimumFractionDigits: 2}));
            return;
        }

        $.ajax({
            url: RECORD_PAYMENT_URL,
            method: 'POST',
            data: {
                payments: payments,
                _token: CSRF_TOKEN
            },
            success: function(response) {
                $('#addPaymentModal').modal('hide');
                alert(response.message);
                location.reload();
            },
            error: function(xhr) {
                let errorMsg = xhr.responseJSON?.error || 'Failed to record payment';
                alert('Error: ' + errorMsg);
            }
        });
    }

    // Update summary when amounts change
    $(document).on('change keyup', '.additional-payment-amount', function() {
        updateAdditionalPaymentSummary();
    });

    // Initialize summary
    updateAdditionalPaymentSummary();

    // Auto-dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush