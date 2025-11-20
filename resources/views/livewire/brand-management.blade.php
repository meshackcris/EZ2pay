<div>
@once
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endonce
@once
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endonce
@once
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endonce
    <div wire:loading.delay class="text-center my-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
  @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
    <div x-data="{ showFilters: false }">
        <!-- Top Bar with Search and Toggle Filter Button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Search brands..." style="max-width: 250px;">

            <div class="d-flex gap-2">
                <button @click="showFilters = !showFilters" class="btn btn-outline-primary btn-xs" title="Toggle Filters">
                    <i class="fas fa-filter"></i>
                </button>
                <button wire:click="$refresh" class="btn btn-outline-primary btn-xs" title="Refresh">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button wire:click="exportToCSV" class="btn btn-outline-primary btn-xs" title="Export">
                    <i class="fas fa-download me-1"></i> Export
                </button>
            </div>
        </div>

        <!-- Filter Panel -->
        <div x-show="showFilters" x-transition.duration.200ms class="p-3 rounded bg-light-subtle border mb-4">
            <div class="row g-2">
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" wire:model="startDate" class="form-control" onclick="this.showPicker && this.showPicker()">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date To</label>
                    <input type="date" wire:model="endDate" class="form-control" onclick="this.showPicker && this.showPicker()">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select
                        wire:model="status"
                        id="brand-status-filter"
                        class="form-select multi-select"
                        multiple="multiple"
                        data-placeholder="Select status"
                        style="width: 100%;"
                    >
                        <option value="1" @selected(in_array(1, $status ?? []))>Active</option>
                        <option value="2" @selected(in_array(2, $status ?? []))>Suspended</option>
                    </select>
                </div>
                
                <div class="col-md-2 d-flex">
                    <button wire:click="clearFilters" class="btn btn-outline-danger btn-xs mt-auto" title="Clear Filters">
                        <i class="fas fa-times me-1"></i> Clear
                    </button>
                    <button wire:click="$refresh" class="btn btn-outline-success btn-xs mt-auto ms-2" title="Apply Filters">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table header-border table-responsive-sm">
            <thead>
                <tr>
                    <th>Registration Date</th>
                    <th>Brand Name</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Registration Link</th>
                    <th>Referrer</th>
                    <th>Deposits Made</th>
                    <th>Withdrawals Made</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                    <tr>
                        <td>{{ $brand->formatted_date }}</td>
                        <td>{{ $brand->brand_name }}</td>
                        <td>{{ $brand->username }}</td>
                        <td>
                            @php
                                $statusLabels = [
                                    2 => ['label' => 'Suspended', 'class' => 'badge-danger'],
                                    1 => ['label' => 'Active', 'class' => 'badge-success'],
                                ];
                                $status = $statusLabels[$brand->status] ?? ['label' => 'Unknown', 'class' => 'badge-secondary'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                        </td>
                        <td>https://user.orion-pay.ca/?ref={{ $brand->ref_token }}</td>
                        <td>{{ $brand->referrer }}</td>
                        <td>${{ number_format($brand->deposits_made, 2) }}</td>
                        <td>${{ number_format($brand->withdrawals_made, 2) }}</td>
                        <td>
                        <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination & Controls -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            <label for="perPage" class="me-2">Show</label>
            <select wire:model.live="perPage" class="form-select d-inline w-auto">
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="2000">2000</option>
            </select>
            <span class="ms-1">results per page</span>
        </div>

        <div class="mt-4 pagination-wrapper">
            {{ $brands->links() }}
        </div>
    </div>

    <div class="mt-2 text-muted small">
        Showing {{ $brands->firstItem() }} to {{ $brands->lastItem() }}
        of {{ $brands->total() }} results
    </div>
    
</div>

<script>
function initBrandStatusSelect() {
    if (!window.jQuery) {
        return;
    }

    const statusSelect = window.jQuery('#brand-status-filter');
    if (!statusSelect.length) {
        return;
    }

    if (statusSelect.hasClass('select2-hidden-accessible')) {
        statusSelect.off('change.select2');
        statusSelect.select2('destroy');
    }

    statusSelect.select2({
        placeholder: statusSelect.data('placeholder') || '',
        width: '100%',
        allowClear: true,
        closeOnSelect: false,
    });

    statusSelect.on('change.select2', function () {
        let val = statusSelect.val() || [];
        val = val.filter((value) => value !== '').map((value) => parseInt(value, 10));
        @this.set('status', val);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initBrandStatusSelect();
});

window.addEventListener('livewire-updated', () => {
    setTimeout(() => {
        initBrandStatusSelect();
    }, 200);
});
</script>
