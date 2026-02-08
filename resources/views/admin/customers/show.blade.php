@extends('admin.layouts.app')

@section('title', 'Customer Details')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">Customer Details</h4>
                <div>
                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-warning">
                        <i class="mdi mdi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Customer Information</h4>
                    <div class="mb-3">
                        <strong>Name:</strong><br>
                        {{ $customer->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong><br>
                        {{ $customer->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Phone:</strong><br>
                        {{ $customer->phone ?? 'N/A' }}
                    </div>
                    <div class="mb-3">
                        <strong>Member Since:</strong><br>
                        {{ $customer->created_at->format('d M, Y') }}
                    </div>
                    <div class="mb-3">
                        <strong>Total Orders:</strong><br>
                        <span class="badge badge-info">{{ $customer->orders->count() }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Total Spent:</strong><br>
                        ₦{{ number_format($customer->orders()->where('status', 'delivered')->sum('total'), 2) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Recent Orders</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customer->orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('d M, Y') }}</td>
                                        <td>₦{{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $order->status_color }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No orders yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection