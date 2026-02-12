@extends('admin.layouts.app')
@section('title', 'Stock Taking')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold mb-0">Stock Taking</h4>
                    <p class="text-muted">Manage periodic stock counts</p>
                </div>
                <a href="{{ route('admin.stock-takes.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-clipboard-check"></i> New Stock Take
                </a>
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

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Type</th>
                                    <th>Period</th>
                                    <th>Products</th>
                                    <th>Discrepancies</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockTakes as $stockTake)
                                    <tr>
                                        <td>
                                            <strong>{{ $stockTake->reference }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-info text-capitalize">
                                                {{ $stockTake->type }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>
                                                {{ $stockTake->period_start->format('d M Y') }}
                                                →
                                                {{ $stockTake->period_end->format('d M Y') }}
                                            </small>
                                        </td>
                                        <td>{{ $stockTake->items->count() }}</td>
                                        <td>
                                            @if($stockTake->status === 'completed')
                                                <span class="badge badge-{{ $stockTake->discrepancy_count > 0 ? 'warning' : 'success' }}">
                                                    {{ $stockTake->discrepancy_count }} items
                                                </span>
                                            @else
                                                <span class="text-muted">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $stockTake->status_color }}">
                                                {{ ucfirst($stockTake->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $stockTake->createdBy->name ?? 'N/A' }}</td>
                                        <td>{{ $stockTake->created_at->format('d M, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.stock-takes.show', $stockTake->id) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.stock-takes.export', $stockTake->id) }}"
                                               class="btn btn-sm btn-success">
                                                <i class="mdi mdi-file-excel"></i>
                                            </a>
                                            @if($stockTake->status !== 'completed')
                                                <button class="btn btn-sm btn-danger"
                                                        onclick="deleteStockTake({{ $stockTake->id }})">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="mdi mdi-clipboard-check mdi-48px text-muted"></i>
                                            <p class="text-muted mt-2">No stock takes found. Start your first one!</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $stockTakes->links() }}
                </div>
            </div>
        </div>
    </div>

    <form id="delete-form" method="POST" style="display:none;">
        @csrf @method('DELETE')
    </form>
@endsection

@push('custom-scripts')
<script>
    function deleteStockTake(id) {
        if (confirm('Are you sure you want to delete this stock take?')) {
            const form = document.getElementById('delete-form');
            form.action = '/admin/stock-takes/' + id;
            form.submit();
        }
    }
</script>
@endpush