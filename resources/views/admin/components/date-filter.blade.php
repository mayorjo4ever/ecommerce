<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ $action }}" id="date-filter-form">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label>From Date</label>
                    <input type="date" class="form-control" name="date_from" 
                           value="{{ request('date_from', $defaultFrom ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label>To Date</label>
                    <input type="date" class="form-control" name="date_to" 
                           value="{{ request('date_to', $defaultTo ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label>{{ $extraLabel ?? 'Status' }}</label>
                    <select class="form-control" name="{{ $extraName ?? 'status' }}">
                        <option value="">All {{ $extraLabel ?? 'Statuses' }}</option>
                        @foreach($extraOptions ?? [] as $value => $label)
                            <option value="{{ $value }}" 
                                {{ request($extraName ?? 'status') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="mdi mdi-filter"></i> Filter
                        </button>
                        <a href="{{ $action }}" class="btn btn-secondary mr-2">
                            <i class="mdi mdi-refresh"></i> Reset
                        </a>
                        <button type="button" class="btn btn-success" onclick="exportResults('{{ $exportRoute ?? '' }}')">
                            <i class="mdi mdi-file-excel"></i> Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Date Range Buttons -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <label class="mr-2">Quick Select:</label>
                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1" 
                            onclick="setDateRange('today')">Today</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1" 
                            onclick="setDateRange('yesterday')">Yesterday</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1" 
                            onclick="setDateRange('this_week')">This Week</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1" 
                            onclick="setDateRange('last_week')">Last Week</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1" 
                            onclick="setDateRange('this_month')">This Month</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1" 
                            onclick="setDateRange('last_month')">Last Month</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1" 
                            onclick="setDateRange('this_year')">This Year</button>
                </div>
            </div>
        </form>
    </div>
</div>