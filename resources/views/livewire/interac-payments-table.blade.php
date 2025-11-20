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

    <div x-data="{ showFilters: false }">
        <!-- Top Bar with Search and Toggle Filter Button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Search transactions..." style="max-width: 250px;">

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

        <!-- Filter Panel (Alpine toggled) -->
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
                @php
                    $statusOptions = [
                        ['value' => 3, 'label' => 'Pending'],
                        ['value' => 4, 'label' => 'Processing'],
                        ['value' => 7, 'label' => 'Completed'],
                        ['value' => 9, 'label' => 'Failed'],
                        ['value' => 17, 'label' => 'Error'],
                    ];
                @endphp
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select
                        wire:model="status"
                        id="interac-status-filter"
                        class="form-select multi-select"
                        multiple="multiple"
                        data-placeholder="Select status"
                        style="width: 100%;"
                    >
                        @foreach($statusOptions as $option)
                            <option value="{{ $option['value'] }}" @selected(in_array($option['value'], $status ?? []))>
                                {{ $option['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Min Amount</label>
                    <input type="number" wire:model="minAmount" class="form-control" placeholder="0.00">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Max Amount</label>
                    <input type="number" wire:model="maxAmount" class="form-control" placeholder="0.00">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Direction</label>
                    <select
                        wire:model="direction"
                        id="interac-direction-filter"
                        class="form-select multi-select"
                        multiple="multiple"
                        data-placeholder="Select direction"
                        style="width: 100%;"
                    >
                        <option value="1" @selected(in_array(1, $direction ?? []))>Collection</option>
                        <option value="0" @selected(in_array(0, $direction ?? []))>Admin Disbursement</option>
                        <option value="3" @selected(in_array(3, $direction ?? []))>Withdrawal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Crypto</label>
                    <select
                        wire:model="cryptoCurrency"
                        id="interac-crypto-filter"
                        class="form-select multi-select"
                        multiple="multiple"
                        data-placeholder="Select crypto"
                        style="width: 100%;"
                    >
                        <option value="BTC" @selected(in_array('BTC', $cryptoCurrency ?? []))>BTC</option>
                        <option value="USDT" @selected(in_array('USDT', $cryptoCurrency ?? []))>USDT</option>
                        <option value="ETH" @selected(in_array('ETH', $cryptoCurrency ?? []))>ETH</option>
                        <option value="SOL" @selected(in_array('SOL', $cryptoCurrency ?? []))>SOL</option>
                        <option value="ADA" @selected(in_array('ADA', $cryptoCurrency ?? []))>ADA</option>
                        <option value="DOGE" @selected(in_array('DOGE', $cryptoCurrency ?? []))>DOGE</option>
                    </select>
                </div>
                <div class="col-md-2">
            <label class="form-label">Brands</label>
            <select
                wire:model="selectedBrand"
                id="interac-brand-filter"
                class="form-select multi-select"
                multiple="multiple"
                data-placeholder="Select brands"
                style="width: 100%;"
            >
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" @selected(in_array($brand->id, $selectedBrand ?? []))>
                        {{ $brand->brand_name }}
                    </option>
                @endforeach
            </select>
        </div>
                <div class="col-md-2 d-flex">
                    <button wire:click="clearFilters" class="btn btn-outline-danger btn-xs mt-auto" title="Clear Filters">
                        <i class="fas fa-times me-1"></i> Clear
                    </button>
                    <button wire:click="$refresh" class="btn btn-outline-success btn-xs mt-auto" style="margin-left:5px;" title="Apply Filters">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table header-border table-responsive-sm">
            <thead>
                <tr>
                    <th>Transaction Date</th>
                    <th>Status</th>
                    <th>Order ID</th>
                    <th>User Name</th>
                    <th>Direction</th>
                    <th>Amount</th>
                    <th>Brand</th>
                    <th>Crypto Currency</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $txn)
                    <tr>
                        <td>{{ $txn->formatted_date }}</td>
                        <td>
                @php
                    $statusLabels = [
                        9 => ['label' => 'Failed', 'class' => 'badge-danger'],
                        17 => ['label' => 'Error', 'class' => 'badge-danger'],
                        4 => ['label' => 'Processing', 'class' => 'badge badge-yellow'],
                        7 => ['label' => 'Completed', 'class' => 'badge-success'],
                        3 => ['label' => 'Pending', 'class' => 'badge-warning'],
                    ];
                    $status = $statusLabels[$txn->Status] ?? ['label' => 'Unknown', 'class' => 'badge-secondary'];
                @endphp
                <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
            </td>
                        <td>{{ $txn->ReferenceNumber }}</td>
                        <td>{{ $txn->legacyUser?->FullName ?? 'Unknown User' }}</td>
                        {{-- ✅ PaymentDirection --}}
            <td>
                @php
                    if ($txn->PaymentDirection == 0) {
                        $direction = 'Disbursement';
                    } elseif ($txn->PaymentDirection == 1) {
                        $direction = 'Collection';
                    } elseif ($txn->PaymentDirection == 3) {
                        $direction = 'Withdrawal';
                    } else {
                        $direction = 'Unknown';
                    }
                @endphp
                {{ $direction }}
            </td>
                        <td>${{ number_format($txn->Amount, 2) }}</td>
                        
            <td>    {{ $txn->legacyUser->brand->brand_name ?? '-' }}
                        <td>{{ strtoupper($txn->CryptoCurrency ?? 'N/A') }}</td>
                       <td>
                            @php $typeMap = [1 => 'Wire', 2 => 'Interact', 3 => 'Withdrawal', 4 => 'Credit Card', 5 => 'Manual Wire', 6 => 'Manual EFT']; @endphp
                {{ $typeMap[$txn->TransactionType] ?? 'Manual Withdrawal' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

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

        <style>
            .pagination {
                @apply text-white;
            }
            .pagination .active span {
                @apply bg-gray-300 text-black rounded px-3 py-1;
            }
        </style>

        <div class="mt-4 pagination-wrapper">
            {{ $transactions->links() }}
        </div>
    </div>

    <div class="mt-2 text-muted small">
        Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }}
        of {{ $transactions->total() }} results
    </div>
</div>

<script>
function initInteracMultiSelects() {
    if (!window.jQuery) {
        return;
    }

    const statusSelect = window.jQuery('#interac-status-filter');
    if (statusSelect.length) {
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

    const directionSelect = window.jQuery('#interac-direction-filter');
    if (directionSelect.length) {
        if (directionSelect.hasClass('select2-hidden-accessible')) {
            directionSelect.off('change.select2');
            directionSelect.select2('destroy');
        }

        directionSelect.select2({
            placeholder: directionSelect.data('placeholder') || '',
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
        });

        directionSelect.on('change.select2', function () {
            let val = directionSelect.val() || [];
            val = val.filter((value) => value !== '').map((value) => parseInt(value, 10));
            @this.set('direction', val);
        });
    }

    const cryptoSelect = window.jQuery('#interac-crypto-filter');
    if (cryptoSelect.length) {
        if (cryptoSelect.hasClass('select2-hidden-accessible')) {
            cryptoSelect.off('change.select2');
            cryptoSelect.select2('destroy');
        }

        cryptoSelect.select2({
            placeholder: cryptoSelect.data('placeholder') || '',
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
        });

        cryptoSelect.on('change.select2', function () {
            let val = cryptoSelect.val() || [];
            @this.set('cryptoCurrency', val);
        });
    }

    const brandSelect = window.jQuery('#interac-brand-filter');
    if (brandSelect.length) {
        if (brandSelect.hasClass('select2-hidden-accessible')) {
            brandSelect.off('change.select2');
            brandSelect.select2('destroy');
        }

        brandSelect.select2({
            placeholder: brandSelect.data('placeholder') || '',
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
        });

        brandSelect.on('change.select2', function () {
            let val = brandSelect.val() || [];
            val = val.filter((value) => value !== '').map((value) => parseInt(value, 10));
            @this.set('selectedBrand', val);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initInteracMultiSelects();
});

window.addEventListener('livewire-updated', () => {
    setTimeout(() => {
        initInteracMultiSelects();
    }, 200);
});
</script>
