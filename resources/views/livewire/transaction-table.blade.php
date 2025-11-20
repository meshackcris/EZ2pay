<div>
<div wire:loading.delay class="text-center my-3">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div x-data="{ showFilters: false }">
    <!-- Top Bar with Search and Toggle Filter Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Search transactions..." style="max-width: 250px;">

        <div class="d-flex gap-2">
            <!-- Filter Toggle Icon Button (now Alpine powered) -->
            <button @click="showFilters = !showFilters" class="btn btn-outline-primary btn-xs" title="Toggle Filters">
                <i class="fas fa-filter"></i>
            </button>
            <!-- Optional: Reload or Export buttons -->
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
        @php
            $statusOptions = [
                ['value' => 3, 'label' => 'Pending'],
                ['value' => 4, 'label' => 'Processing'],
                ['value' => 7, 'label' => 'Completed'],
                ['value' => 9, 'label' => 'Failed'],
                ['value' => 17, 'label' => 'Error'],
            ];

            $directionOptions = [
                ['value' => 1, 'label' => 'Collection'],
                ['value' => 0, 'label' => 'Admin Disbursement'],
                ['value' => 3, 'label' => 'Withdrawal'],
            ];

            $transactionTypeOptions = [
                ['value' => 1, 'label' => 'Wire'],
                ['value' => 2, 'label' => 'Interact'],
                ['value' => 3, 'label' => 'Withdrawal'],
                ['value' => 4, 'label' => 'Credit Card'],
                ['value' => 5, 'label' => 'Manual Wire'],
                ['value' => 6, 'label' => 'Manual Eft'],
            ];
        @endphp
        <div class="row g-2">
            <div class="col-md-2">
                <label class="form-label">Date From</label>
                <input type="date" wire:model="startDate" class="form-control" onclick="this.showPicker && this.showPicker()"
>
            </div>
            <div class="col-md-2">
                <label class="form-label">Date To</label>
                <input type="date" wire:model="endDate" class="form-control" onclick="this.showPicker && this.showPicker()">
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select wire:model="status" id="status" class="form-control multi-select" multiple="multiple" data-placeholder="Select status" style="width: 100%;">
                    @foreach($statusOptions as $statusOption)
                        <option value="{{ $statusOption['value'] }}" @selected(in_array($statusOption['value'], $status ?? []))>
                            {{ $statusOption['label'] }}
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
            <div class="col-md-3">
                <label class="form-label">Direction</label>
                <select wire:model="direction" id="direction" class="form-select multi-select" multiple="multiple" data-placeholder="Select direction" style="width: 100%;">
                    @foreach($directionOptions as $directionOption)
                        <option value="{{ $directionOption['value'] }}" @selected(in_array($directionOption['value'], $direction ?? []))>
                            {{ $directionOption['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Crypto</label>
                <select wire:model="cryptoCurrency" class="form-select">
                    <option value="">All</option>
                    <option value="BTC">BTC</option>
                    <option value="BTC">USDT</option>
                    <option value="ETH">ETH</option>
                    <option value="SOL">SOL</option>
                    <option value="ADA">ADA</option>
                    <option value="DOGE">DOGE</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select wire:model="transactionType" id="type" class="form-select multi-select" multiple="multiple" data-placeholder="Select type" style="width: 100%;">
                    @foreach($transactionTypeOptions as $typeOption)
                        <option value="{{ $typeOption['value'] }}" @selected(in_array($typeOption['value'], $transactionType ?? []))>
                            {{ $typeOption['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Brands</label>
                <select wire:model="selectedBrands" id="brands" class="form-select multi-select" multiple="multiple" data-placeholder="Select brands" style="width: 100%;">
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(in_array($brand->id, $selectedBrands ?? []))>
                            {{ $brand->brand_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex">
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
                            <th>POP</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
    @foreach($transactions as $txn)
        <tr>
<td>{{ $txn->formatted_date }}</td>

            {{-- ✅ Status --}}
<td 
    @if ($txn->ErrorMessage) 
        title="{{ $txn->ErrorMessage }}" 
        data-bs-toggle="tooltip"
    @endif
>            @php
                $statusLabels = [
                    9 => ['label' => 'Failed', 'class' => 'badge-danger'],
                    17 => ['label' => 'Error', 'class' => 'badge-danger'],
                    4 => ['label' => 'Processing', 'class' => 'badge-yellow'],
                    7 => ['label' => 'Completed', 'class' => 'badge-success'],
                    3 => ['label' => 'Pending', 'class' => 'badge-warning'],

                ];
            @endphp

            <select
    wire:change="updateTransactionStatus('{{ $txn->Id }}', $event.target.value)"
    id="statusSelect-{{ $txn->Id }}"
    class="form-select form-select-sm w-auto dynamic-status"
    data-txn-id="{{ $txn->Id }}"
>
    @foreach($statusLabels as $key => $labelData)
        <option
            value="{{ $key }}"
            data-custom-class="{{ $labelData['class'] }}"
            {{ $txn->Status == $key ? 'selected' : '' }}
        >
            {{ $labelData['label'] }}
        </option>
    @endforeach
</select>

            </td>

            {{-- ✅ Order ID --}}
            <td>{{ $txn->ReferenceNumber }}</td>

            <td>
                {{ $txn->legacyUser?->FullName ?? 'Unknown User' }}
            </td>

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


            {{-- ✅ Amount --}}
            <td>${{ number_format($txn->Amount, 2) }}</td>

            <td>    {{ $txn->legacyUser->brand->brand_name ?? '-' }}
</td>

            {{-- ✅ CryptoCurrency --}}
            <td>{{ strtoupper($txn->CryptoCurrency ?? 'N/A') }}</td>

            {{-- ✅ TransactionType --}}
            <td>
                @php
                    $typeMap = [1 => 'Wire', 2 => 'Interact', 3 => 'Withdrawal', 4 => 'Credit Card', 5 => 'Manual Wire', 6 => 'Manual EFT'];
                @endphp
                {{ $typeMap[$txn->TransactionType] ?? 'Unknown' }}
            </td>
            <td>
                @if($txn->Pop)
                    <a href="https://orion-pay.tor1.cdn.digitaloceanspaces.com/pop/{{$txn->Pop}}" target="_blank" class="btn btn-outline-primary btn-xs">
                        <i class="fas fa-eye"></i> View POP
                    </a> 
                    @else
                       <button type="button" class="btn btn-primary me-2" onclick="showPopModal('{{ $txn->Id }}')" >Upload POP</button>

                    @endif   
                 </td>
                 <td>
                @if($txn->Pop)
                     <button type="button" class="btn btn-danger " wire:click="deletePOP('{{ $txn->Id }}')" >Delete POP</button>
               
                    @endif   
                 </td>
        </tr>
        
    @endforeach
</tbody>

                </table>
            </div>

           <div class="d-flex justify-content-between align-items-center mt-4">
    <!-- Per page dropdown -->
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

<!-- Result count below -->
<div class="mt-2 text-muted small">
    Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }}
    of {{ $transactions->total() }} results
</div>

<!-- Pop up Modal for POP Upload -->

<div class="modal fade" id="popModal" tabindex="-1" aria-labelledby="popModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" x-data="{ idType: '' }">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="popModalLabel">Manual KYC Upload</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

<form action="{{ route('submit.pop') }}" class="m-4" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Upload Proof of Payment</label>

          <input type="file" id="popUser" name="popUser">
    </div>
              <input type="hidden" name="txnId" id="txnId">


    <button type="submit"
        class="btn btn-primary">Upload POP</button>

                    </form>
    </div>
  </div>
</div>

<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

<script>
    function initFilePondWhenReady() {
    const inputElement = document.getElementById('popUser');
    if (inputElement) {
    // Create a FilePond instance
    const pond = FilePond.create(inputElement);
    FilePond.setOptions({
    server: {
        process: '/pop-tmp-upload',
        revert: '/pop-tmp-delete',
        headers: {
            'X-CSRF-TOKEN' : '{{ csrf_token() }}'
        }
    },
});
    } else {
        setTimeout(initFilePondWhenReady, 500); // Retry after 500ms
    }
}

initFilePondWhenReady();

</script>



<script>
function initChoicesDropdowns() {
    document.querySelectorAll('select.dynamic-status').forEach(el => {
        if (!el.classList.contains('choices-initialized')) {
            const instance = new Choices(el, {
                itemSelectText: '',
                shouldSort: false
            });
            
            setTimeout(() => {
    document.querySelectorAll('.choices').forEach(choice => {
    const selected = choice.querySelector('.choices__item--selectable[aria-selected="true"]');
    const inner = choice.querySelector('.choices__inner');
    if (selected && inner) {
        const value = selected.getAttribute('data-value');

        // Clear old badge classes
        inner.classList.remove('badge-success', 'badge-danger', 'badge-info');

        // Add new class based on value
        // Remove all possible badge classes first
        inner.classList.remove(
            'badge-danger',
            'badge-yellow',
            'badge-success',
            'badge-warning'
        );

        // Apply based on value
        switch (value) {
            case '9':   // Failed
                inner.classList.add('badge-danger');
                break;
            case '17':  // Error
                inner.classList.add('badge-danger');
                break;
            case '4':   // Processing
                inner.classList.add('badge-yellow');
                break;
            case '7':   // Completed
                inner.classList.add('badge-success');
                break;
            case '3':   // Pending
                inner.classList.add('badge-warning');
                break;
        }
    }
});

    const options = el.querySelectorAll('option');
    const wrapper = el.closest('.choices');
    const choiceItems = wrapper.querySelectorAll('.choices__item--choice');


    choiceItems.forEach((item, index) => {
        const cls = options[index]?.getAttribute('data-custom-class');
        if (cls) {
            item.classList.add(cls);
        }
    });
}, 300); // 300ms is usually sufficient — 10s is overkill


            el.classList.add('choices-initialized');
        }
    });
}

document.addEventListener('DOMContentLoaded', initChoicesDropdowns);

 window.addEventListener('livewire-updated', () => {
        // Re-initialize Choices.js for dynamic status dropdowns
        setTimeout(() => {
            initChoicesDropdowns();
            $('.multi-select').select2();
        }, 300); // Short delay to ensure DOM is ready
    });

</script>
@script()
<script>
$(document).ready(function() {
    $('#status').on('change', function (e) {
        let val = $(this).val() || []; // Always returns array (or empty array)
        @this.set('status', val);      // Update Livewire property safely
    });
    $('#type').on('change', function (e) {
        let val = $(this).val() || []; // Always returns array (or empty array)
        @this.set('transactionType', val);      // Update Livewire property safely
    });
    $('#direction').on('change', function (e) {
        let val = $(this).val() || []; // Always returns array (or empty array)
        @this.set('direction', val);      // Update Livewire property safely
    });
    $('#brands').on('change', function (e) {
        let val = $(this).val() || []; // Always returns array (or empty array)
        @this.set('selectedBrands', val);      // Update Livewire property safely
    });
});
</script>
    @endscript
<script>
    function showPopModal(txnId) {
            // Set hidden input or text in modal if needed
            const modal = new bootstrap.Modal(document.getElementById('popModal'));
            modal.show();
                document.getElementById('txnId').value = txnId;
    }
</script>

        </div>
