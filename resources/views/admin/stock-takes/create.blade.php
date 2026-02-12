@extends('admin.layouts.app')
@section('title', 'New Stock Take')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="font-weight-bold mb-0">Start New Stock Take</h4>
                <a href="{{ route('admin.stock-takes.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Stock Take Configuration</h4>

                    <div class="alert alert-info">
                        <i class="mdi mdi-information"></i>
                        This will create a stock take with all
                        <strong>{{ $productCount }} active products</strong>.
                        You can then count and enter the physical quantities.
                    </div>

                    <form action="{{ route('admin.stock-takes.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Stock Take Type <span class="text-danger">*</span></label>
                            <div class="row">
                                @foreach([
                                    'weekly' => ['Weekly', 'mdi-calendar-week', 'Every 7 days count'],
                                    'monthly' => ['Monthly', 'mdi-calendar-month', 'End of month count'],
                                    'yearly' => ['Yearly', 'mdi-calendar', 'Annual stock audit'],
                                    'custom' => ['Custom', 'mdi-calendar-edit', 'Custom date range'],
                                ] as $value => [$label, $icon, $desc])
                                    <div class="col-md-3 mb-3">
                                        <div class="card stock-type-card border cursor-pointer"
                                             onclick="selectType('{{ $value }}')"
                                             id="type-card-{{ $value }}"
                                             style="cursor:pointer;">
                                            <div class="card-body text-center p-3">
                                                <i class="mdi {{ $icon }} mdi-36px text-primary"></i>
                                                <h6 class="mt-2 mb-1">{{ $label }}</h6>
                                                <small class="text-muted">{{ $desc }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="type" id="selected-type"
                                   value="{{ old('type', 'monthly') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Period Start <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('period_start') is-invalid @enderror"
                                           name="period_start" id="period-start"
                                           value="{{ old('period_start') }}" required>
                                    @error('period_start')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Period End <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('period_end') is-invalid @enderror"
                                           name="period_end" id="period-end"
                                           value="{{ old('period_end') }}" required>
                                    @error('period_end')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="form-control" name="notes" rows="3"
                                      placeholder="Optional notes about this stock take...">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="mdi mdi-clipboard-check"></i> Start Stock Take
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    function selectType(type) {
        // Remove active from all cards
        document.querySelectorAll('.stock-type-card').forEach(card => {
            card.classList.remove('border-primary', 'bg-light');
        });

        // Add active to selected
        document.getElementById('type-card-' + type).classList.add('border-primary', 'bg-light');
        document.getElementById('selected-type').value = type;

        // Auto-set dates based on type
        const today = new Date();
        let start, end;

        switch(type) {
            case 'weekly':
                start = new Date(today);
                start.setDate(today.getDate() - today.getDay()); // Start of week
                end = new Date(today);
                break;
            case 'monthly':
                start = new Date(today.getFullYear(), today.getMonth(), 1);
                end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                break;
            case 'yearly':
                start = new Date(today.getFullYear(), 0, 1);
                end = new Date(today.getFullYear(), 11, 31);
                break;
            case 'custom':
                // Don't auto-set
                return;
        }

        document.getElementById('period-start').value = formatDate(start);
        document.getElementById('period-end').value = formatDate(end);
    }

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Initialize with monthly
    selectType('{{ old('type', 'monthly') }}');
</script>
@endpush