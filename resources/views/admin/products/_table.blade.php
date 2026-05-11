{{--
    Partial: admin/products/_table.blade.php
    Rendered both on initial page load and via AJAX.
    Receives: $products (LengthAwarePaginator)
--}}
<div class="table-responsive">
    <table class="table table-hover" id="products-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    {{-- Image --}}
                    <td>
                        @if($product->featured_image)
                            <img src="{{ asset('storage/' . $product->featured_image) }}"
                                 alt="{{ $product->name }}"
                                 style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                 style="width:50px;height:50px;border-radius:6px;">
                                <i class="mdi mdi-image text-muted"></i>
                            </div>
                        @endif
                    </td>

                    {{-- Name --}}
                    <td class="font-weight-medium">{{ $product->name }}</td>

                    {{-- SKU --}}
                    <td><code style="font-size:12px;">{{ $product->sku }}</code></td>

                    {{-- Category --}}
                    <td>{{ $product->category->name ?? '—' }}</td>

                    {{-- Price --}}
                    <td>₦{{ number_format($product->price, 2) }}
                        @if($product->sale_price)
                            <br><small class="text-danger">
                                Sale: ₦{{ number_format($product->sale_price, 2) }}
                            </small>
                        @endif
                    </td>

                    {{-- Stock badge --}}
                    <td>
                        @php
                            $qty   = $product->quantity;
                            $cls   = $qty > 10 ? 'badge-stock-ok' : ($qty > 0 ? 'badge-stock-low' : 'badge-stock-out');
                            $label = $qty > 10 ? 'In Stock' : ($qty > 0 ? 'Low' : 'Out');
                        @endphp
                        <span class="badge {{ $cls }}" style="font-size:11px;padding:4px 8px;">
                            {{ $qty }} &nbsp;<em style="font-style:normal;opacity:.8;">{{ $label }}</em>
                        </span>
                    </td>

                    {{-- Status --}}
                    <td>
                        <span class="badge badge-{{ $product->is_active ? 'success' : 'secondary' }}"
                              style="font-size:11px;padding:4px 8px;">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <a href="{{ route('admin.products.show', $product) }}"
                           class="btn btn-sm btn-outline-secondary"
                           data-toggle="tooltip" title="View">
                            <i class="mdi mdi-eye"></i>
                        </a>

                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="btn btn-sm btn-info"
                           data-toggle="tooltip" title="Edit">
                            <i class="mdi mdi-pencil"></i>
                        </a>

                        <a href="{{ route('admin.stock.history', $product) }}"
                           class="btn btn-sm btn-outline-success"
                           data-toggle="tooltip" title="Stock History">
                            <i class="mdi mdi-package-variant"></i>
                        </a>

                        <form action="{{ route('admin.products.destroy', $product) }}"
                              method="POST"
                              style="display:inline-block;"
                              onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                    data-toggle="tooltip" title="Delete">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="mdi mdi-package-variant-closed" style="font-size:2rem;"></i>
                        <br>No products found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($products->hasPages())
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }}
            of {{ $products->total() }} products
        </small>
        {{ $products->appends(request()->query())->links() }}
    </div>
@endif