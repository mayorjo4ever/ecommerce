@extends('admin.layouts.app')
@section('title', 'Stock Take Details')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold mb-0">Stock Take: {{ $stockTake->reference }}</h4>
                    <p class="text-muted mb-0">
                        {{ $stockTake->period_start->format('d M Y') }} →
                        {{ $stockTake->period_end->format('d M Y') }}
                    </p>
                </div>
                <div>
                    @if($stockTake->status !== 'completed')
                        <form action="{{ route('admin.stock-takes.complete', $stockTake->id) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Complete this stock take? Product quantities will be updated.')">
                                <i class="mdi mdi-check-circle"></i> Complete Stock Take
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.stock-takes.export', $stockTake->id) }}"
                       class="btn btn-info">
                        <i class="mdi mdi-file-excel"></i> Export
                    </a>
                    <a href="{{ route('admin.stock-takes.index') }}" class="btn btn-secondary">
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
            @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <!-- Progress -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <p class="text-muted small mb-1">Progress</p>
                    <h4 class="mb-0">{{ $progress }}%</h4>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: {{ $progress }}%"></div>
                    </div>
                    <small class="text-muted">{{ $counted }}/{{ $total }} counted</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-{{ $stockTake->status_color }}">
                <div class="card-body">
                    <p class="text-muted small mb-1">Status</p>
                    <span class="badge badge-{{ $stockTake->status_color }} badge-lg">
                        {{ ucfirst($stockTake->status) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <p class="text-muted small mb-1">Discrepancies</p>
                    <h4 class="mb-0">{{ $stockTake->discrepancy_count }}</h4>
                    <small class="text-muted">items with variance</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Variance</p>
                    <h4 class="mb-0 {{ $stockTake->total_variance < 0 ? 'text-danger' : 'text-success' }}">
                        {{ $stockTake->total_variance > 0 ? '+' : '' }}{{ $stockTake->total_variance }}
                    </h4>
                    <small class="text-muted">units</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="d-flex align-items-center">
                <label class="mr-3 mb-0">Show:</label>
                <button class="btn btn-sm btn-primary mr-2" onclick="filterItems('all')">
                    All ({{ $total }})
                </button>
                <button class="btn btn-sm btn-warning mr-2" onclick="filterItems('uncounted')">
                    Uncounted ({{ $total - $counted }})
                </button>
                <button class="btn btn-sm btn-danger mr-2" onclick="filterItems('shortage')">
                    Shortage
                </button>
                <button class="btn btn-sm btn-info mr-2" onclick="filterItems('excess')">
                    Excess
                </button>
                <div class="ml-auto">
                    <input type="text" class="form-control form-control-sm"
                           id="item-search" placeholder="Search product...">
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Take Items -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="stock-items-table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>System Qty</th>
                            <th>Physical Qty</th>
                            <th>Variance</th>
                            <th>Notes</th>
                            @if($stockTake->status !== 'completed')
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockTake->items as $item)
                            <tr class="stock-item"
                                data-counted="{{ $item->physical_quantity !== null ? 'yes' : 'no' }}"
                                data-variance="{{ $item->variance ?? 0 }}">
                                <td>{{ $item->product->sku ?? 'N/A' }}</td>
                                <td>
                                    <strong>{{ $item->product->name ?? 'Deleted Product' }}</strong>
                                </td>
                                <td>{{ $item->product->category->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $item->system_quantity }}
                                    </span>
                                </td>
                                <td id="physical-{{ $item->id }}">
                                    @if($item->physical_quantity !== null)
                                        <span class="badge badge-success">
                                            {{ $item->physical_quantity }}
                                        </span>
                                    @else
                                        <span class="badge badge-warning">Not counted</span>
                                    @endif
                                </td>
                                <td id="variance-{{ $item->id }}">
                                    @if($item->variance !== null)
                                        <span class="badge badge-{{ $item->variance == 0 ? 'success' : ($item->variance > 0 ? 'info' : 'danger') }}">
                                            {{ $item->variance > 0 ? '+' : '' }}{{ $item->variance }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td id="notes-{{ $item->id }}">
                                    <small class="text-muted">{{ $item->notes ?? '' }}</small>
                                </td>
                                @if($stockTake->status !== 'completed')
                                    <td>
                                        <button class="btn btn-sm btn-primary"
                                                onclick="openCountModal({{ $item->id }}, '{{ addslashes($item->product->name ?? 'N/A') }}', {{ $item->system_quantity }}, {{ $item->physical_quantity ?? 'null' }})">
                                            <i class="mdi mdi-pencil"></i> Count
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Count Modal -->
    @if($stockTake->status !== 'completed')
        <div class="modal fade" id="countModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Count: <span id="modal-product-name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">System Quantity</h6>
                                        <h2 class="mb-0" id="modal-system-qty">0</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h6>Physical Count</h6>
                                        <h2 class="mb-0" id="modal-variance-display">0</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Physical Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-lg"
                                   id="modal-physical-qty" min="0" value="0"
                                   onchange="updateVarianceDisplay()">
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="form-control" id="modal-notes" rows="2"
                                      placeholder="Optional notes..."></textarea>
                        </div>

                        <div id="variance-alert" class="alert" style="display:none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveCount()">
                            <i class="mdi mdi-content-save"></i> Save Count
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('custom-scripts')
<script>
    const STOCK_TAKE_ID = {{ $stockTake->id }};
    const CSRF_TOKEN = '{{ csrf_token() }}';
    let currentItemId = null;
    let currentSystemQty = 0;

    function openCountModal(itemId, productName, systemQty, physicalQty) {
        currentItemId = itemId;
        currentSystemQty = systemQty;

        document.getElementById('modal-product-name').textContent = productName;
        document.getElementById('modal-system-qty').textContent = systemQty;
        document.getElementById('modal-physical-qty').value = physicalQty !== null ? physicalQty : 0;
        document.getElementById('modal-notes').value = '';

        updateVarianceDisplay();
        $('#countModal').modal('show');

        // Focus input after modal opens
        setTimeout(() => document.getElementById('modal-physical-qty').focus(), 500);
    }

    function updateVarianceDisplay() {
        const physical = parseInt(document.getElementById('modal-physical-qty').value) || 0;
        const variance = physical - currentSystemQty;
        const display = document.getElementById('modal-variance-display');
        const alert = document.getElementById('variance-alert');

        display.textContent = physical;

        if (variance === 0) {
            alert.className = 'alert alert-success';
            alert.textContent = '✓ No discrepancy';
            alert.style.display = 'block';
        } else if (variance > 0) {
            alert.className = 'alert alert-info';
            alert.textContent = `+${variance} excess units`;
            alert.style.display = 'block';
        } else {
            alert.className = 'alert alert-danger';
            alert.textContent = `${variance} units shortage`;
            alert.style.display = 'block';
        }
    }

    function saveCount() {
        const physicalQty = parseInt(document.getElementById('modal-physical-qty').value);
        const notes = document.getElementById('modal-notes').value;

        if (isNaN(physicalQty) || physicalQty < 0) {
            alert('Please enter a valid quantity.');
            return;
        }

        $.ajax({
            url: `/admin/stock-takes/${STOCK_TAKE_ID}/items/${currentItemId}`,
            method: 'POST',
            data: {
                physical_quantity: physicalQty,
                notes: notes,
                _token: CSRF_TOKEN
            },
            success: function(response) {
                // Update row
                const variance = physicalQty - currentSystemQty;

                document.getElementById(`physical-${currentItemId}`).innerHTML =
                    `<span class="badge badge-success">${physicalQty}</span>`;

                const varianceBadge = variance === 0 ? 'success' : (variance > 0 ? 'info' : 'danger');
                document.getElementById(`variance-${currentItemId}`).innerHTML =
                    `<span class="badge badge-${varianceBadge}">${variance > 0 ? '+' : ''}${variance}</span>`;

                document.getElementById(`notes-${currentItemId}`).innerHTML =
                    `<small class="text-muted">${notes}</small>`;

                // Update row data attributes
                const row = document.querySelector(`tr[data-counted]`);

                $('#countModal').modal('hide');

                // Update progress
                location.reload();
            },
            error: function() {
                alert('Failed to save count. Please try again.');
            }
        });
    }

    // Filter items
    function filterItems(filter) {
        const rows = document.querySelectorAll('.stock-item');
        rows.forEach(row => {
            const counted = row.dataset.counted;
            const variance = parseInt(row.dataset.variance) || 0;

            switch(filter) {
                case 'all':
                    row.style.display = '';
                    break;
                case 'uncounted':
                    row.style.display = counted === 'no' ? '' : 'none';
                    break;
                case 'shortage':
                    row.style.display = variance < 0 ? '' : 'none';
                    break;
                case 'excess':
                    row.style.display = variance > 0 ? '' : 'none';
                    break;
            }
        });
    }

    // Search
    document.getElementById('item-search')?.addEventListener('keyup', function() {
        const search = this.value.toLowerCase();
        document.querySelectorAll('.stock-item').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(search) ? '' : 'none';
        });
    });
</script>
@endpush