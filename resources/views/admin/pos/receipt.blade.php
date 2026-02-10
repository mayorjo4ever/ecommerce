<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }
        .receipt {
            border: 2px solid #000;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            margin: 2px 0;
        }
        .section {
            margin: 15px 0;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 14px;
        }
        .items-table {
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
        }
        .items-table th {
            border-bottom: 2px solid #000;
            padding: 5px 0;
            text-align: left;
            font-size: 12px;
        }
        .items-table td {
            padding: 8px 0;
            font-size: 13px;
            border-bottom: 1px dashed #ccc;
        }
        .items-table .item-name {
            width: 50%;
        }
        .items-table .item-qty {
            width: 15%;
            text-align: center;
        }
        .items-table .item-price {
            width: 20%;
            text-align: right;
        }
        .items-table .item-total {
            width: 15%;
            text-align: right;
        }
        .totals {
            margin-top: 15px;
            border-top: 2px solid #000;
            padding-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 14px;
        }
        .total-row.grand-total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #000;
        }
        .payment-info {
            margin: 15px 0;
            padding: 10px;
            background: #f0f0f0;
            border: 1px solid #000;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            width: 150px;
            height: 150px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 2px dashed #000;
            padding-top: 15px;
            font-size: 12px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>Address Line 1, City, State</p>
            <p>Phone: +234 XXX XXX XXXX</p>
            <p>Email: info@yourstore.com</p>
        </div>

        <!-- Order Info -->
        <div class="section">
            <div class="info-row">
                <strong>Receipt #:</strong>
                <span>{{ $order->order_number }}</span>
            </div>
            <div class="info-row">
                <strong>Date:</strong>
                <span>{{ $order->created_at->format('d M, Y H:i') }}</span>
            </div>
            <div class="info-row">
                <strong>Cashier:</strong>
                <span>{{ $order->payment->payment_details['processed_by'] ?? 'Admin' }}</span>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="section">
            <div class="section-title">Customer Information</div>
            <div class="info-row">
                <strong>Name:</strong>
                <span>{{ $order->user->name }}</span>
            </div>
            @if($order->user->email && !str_contains($order->user->email, '@walkin.local'))
            <div class="info-row">
                <strong>Email:</strong>
                <span>{{ $order->user->email }}</span>
            </div>
            @endif
            @if($order->user->phone)
            <div class="info-row">
                <strong>Phone:</strong>
                <span>{{ $order->user->phone }}</span>
            </div>
            @endif
        </div>

        <!-- Items -->
        <div class="section">
            <div class="section-title">Items Purchased</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="item-name">Item</th>
                        <th class="item-qty">Qty</th>
                        <th class="item-price">Price</th>
                        <th class="item-total">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td class="item-name">{{ $item->product->name }}</td>
                        <td class="item-qty">{{ $item->quantity }}</td>
                        <td class="item-price">₦{{ number_format($item->price, 2) }}</td>
                        <td class="item-total">₦{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>₦{{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->discount > 0)
            <div class="total-row">
                <span>Discount:</span>
                <span>- ₦{{ number_format($order->discount, 2) }}</span>
            </div>
            @endif
            @if($order->tax > 0)
            <div class="total-row">
                <span>Tax:</span>
                <span>₦{{ number_format($order->tax, 2) }}</span>
            </div>
            @endif
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>₦{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="section-title">Payment Details</div>
            @foreach($order->payments as $payment)
                <div class="info-row">
                    <span class="text-uppercase">{{ $payment->payment_method }}:</span>
                    <span>₦{{ number_format($payment->amount, 2) }}</span>
                </div>
            @endforeach
            <div style="border-top: 1px dashed #000; margin: 5px 0;"></div>
            <div class="info-row">
                <strong>Total Paid:</strong>
                <strong>₦{{ number_format($order->amount_paid, 2) }}</strong>
            </div>
            @if($order->balance > 0)
                <div class="info-row" style="color: red;">
                    <strong>Balance Due:</strong>
                    <strong>₦{{ number_format($order->balance, 2) }}</strong>
                </div>
            @else
                @php
                    $firstPayment = $order->payments->first();
                    $details = is_array($firstPayment?->payment_details)
                        ? $firstPayment->payment_details
                        : json_decode($firstPayment?->payment_details, true);
                    $change = ($details['change'] ?? 0);
                @endphp
                @if($change > 0)
                    <div class="info-row">
                        <strong>Change:</strong>
                        <strong>₦{{ number_format($change, 2) }}</strong>
                    </div>
                @endif
            @endif
            <div class="info-row">
                <strong>Payment Status:</strong>
                <span class="text-uppercase">{{ $order->payment_status }}</span>
            </div>
        </div>

        <!-- QR Code -->
        @if($order->qr_code)
        <div class="qr-code">
            <img src="{{ asset('storage/' . $order->qr_code) }}" alt="Order QR Code">
            <p style="font-size: 10px; margin-top: 5px;">Scan for order details</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for your purchase!</strong></p>
            <p>Please keep this receipt for your records</p>
            <p style="margin-top: 10px;">{{ config('app.name') }} - Serving you better!</p>
        </div>
    </div>

    <!-- Print Buttons -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; margin: 5px; cursor: pointer; font-size: 16px;">
            Print Receipt
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; margin: 5px; cursor: pointer; font-size: 16px;">
            Close
        </button>
    </div>

    <script>
        // Auto-print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>