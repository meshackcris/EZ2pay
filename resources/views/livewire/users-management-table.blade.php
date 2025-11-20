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
    <div>
    <!-- Loading Spinner -->
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
        <input wire:model.defer="search" @keydown.enter="Livewire.dispatch('triggerSearch')" type="text" class="form-control" placeholder="Search Users..." style="max-width: 250px;">

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
            @php
                $statusFilterOptions = [
                    ['value' => 1, 'label' => 'New'],
                    ['value' => 2, 'label' => 'Pending'],
                    ['value' => 3, 'label' => 'Review'],
                    ['value' => 4, 'label' => 'Escalated'],
                    ['value' => 5, 'label' => 'Accepted'],
                    ['value' => 6, 'label' => 'Rejected'],
                    ['value' => 7, 'label' => 'Expired'],
                    ['value' => 8, 'label' => 'Approved'],
                    ['value' => 9, 'label' => 'None'],
                ];
            @endphp
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select
                    wire:model="status"
                    id="user-status-filter"
                    class="form-select multi-select"
                    multiple="multiple"
                    data-placeholder="Select status"
                    style="width: 100%;"
                >
                    @foreach($statusFilterOptions as $option)
                        <option value="{{ $option['value'] }}" @selected(in_array($option['value'], $status ?? []))>
                            {{ $option['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Min Balance</label>
                <input type="number" wire:model="minBalance" class="form-control" placeholder="0.00">
            </div>
            <div class="col-md-2">
                <label class="form-label">Max Balance</label>
                <input type="number" wire:model="maxBalance" class="form-control" placeholder="0.00">
            </div>
           
                <div class="col-md-2">
            <label class="form-label">Brands</label>
            <select
                wire:model="selectedBrand"
                id="user-brand-filter"
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



    <!-- Table -->
    <div class="table-responsive">
        <table class="table header-border table-responsive-sm">
            <thead>
                <tr>
                    <th>Registration Date</th>
                    <th>Status</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Brand</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Balance</th>
                    <th>Total Deposits</th>
                    <th>Total Withdrawals</th>
                    <th>ID Front</th>
                    <th>ID Back</th>
                    <th>POA</th>
                    <th>Video</th>
                    <th>PAD</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody 
    x-data="{
        openRowId: null,
        loadingUserId: null,

        isLoading(userId) {
            return this.loadingUserId === userId || this.loadingUserId === 'bulk';
        },

        async toggleRow(userId) {
            this.openRowId = this.openRowId === userId ? null : userId;
        }
    }"
>



                @foreach($users as $user)
                         <tr @click="toggleRow('{{ $user->Id }}')">
                        <td>{{ $user->CreatedAt ? \Carbon\Carbon::parse($user->CreatedAt)->format('Y-m-d H:i') : 'N/A' }} <i 
        class="fas fa-copy ms-2 text-primary" 
        style="cursor: pointer;" 
        title="Copy date"
        onclick="copyToClipboard('{{ \Carbon\Carbon::parse($user->CreatedAt)->format('Y-m-d H:i') }}')"
    ></i></td>
                        <td>
                            <select
                                wire:change="updateStatus('{{ $user->Id }}', $event.target.value)"
                                class="form-select form-select-sm w-auto dynamic-status"
                                id="user-status-{{ $user->Id }}"
                                data-user-id="{{ $user->Id }}"
                            >
                                @foreach($verificationStatusLabels as $key => $status)
                                    <option
                                        value="{{ $key }}"
                                        {{ $user->VerificationStatus == $key ? 'selected' : '' }}
                                        data-custom-class="{{ $status['class'] ?? '' }}"
                                    >
                                        {{ $status['label'] }}
                                    </option>
                                @endforeach
                            </select>

                        </td>
                        <td>{{ $user->FirstName }}</td>
                        <td>{{ $user->LastName }} <i 
        class="fas fa-copy ms-2 text-primary" 
        style="cursor: pointer;" 
        title="Copy full name"
        onclick="copyToClipboard('{{ $user->FirstName }} {{ $user->LastName }}')"
    ></i></td>
                        <td>{{ $user->brand->brand_name ?? '-' }}</td>
                        <td>{{ $user->Email }} <i 
        class="fas fa-copy ms-2 text-primary" 
        style="cursor: pointer;" 
        title="Copy email"
        onclick="copyToClipboard('{{ $user->Email }}')"
    ></i></td>
                        <td>{{ $user->PhoneNumber }}</td>
                        <td>${{ number_format($user->Balance, 2) }}</td>
                        <td>${{ number_format($user->total_deposits, 2) }}</td>
                        <td>${{ number_format($user->total_withdrawals, 2) }}</td>
                        @php
                            $kyc = \App\Models\KycSubmission::where('user_id', $user->Id)->where('status', 1)->first();
                            if ($kyc) {
                                $baseUrl = 'https://orion-pay.tor1.cdn.digitaloceanspaces.com/';
                            }
                        @endphp

                        <td>
                            @if ($kyc)
                                <a href="{{ $baseUrl . $kyc->document_front_path }}" target="_blank" title="View Document">
                                    <i class="bi bi-file-earmark-text" style="font-size: 1.5rem;"></i>
                                </a>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                        <td>
                            @if ($kyc)
                                <a href="{{ $baseUrl . $kyc->document_back_path }}" target="_blank" title="View Document">
                                    <i class="bi bi-file-earmark-text" style="font-size: 1.5rem;"></i>
                                </a>
                            @else
                                <span>-</span>
                            @endif
                        </td>

                        <td>
                            @if ($kyc)
                                <a href="{{ $baseUrl . $kyc->poa_path }}" target="_blank" title="View Document">
                                    <i class="bi bi-file-earmark-text" style="font-size: 1.5rem;"></i>
                                </a>
                            @else
                                <span>-</span>
                            @endif
                        </td>

                        <td>
                            @if ($kyc)
                               <a href="{{ $baseUrl . 'kyc_videos/' . $kyc->video_path }}" target="_blank" title="View Video">
                                    <i class="bi bi-play-circle" style="font-size: 1.5rem;"></i>
                                </a>
                            @else
                                <span>-</span>
                            @endif
                        </td>

                        <td>
                            @if ($user->pad_agreement_path)
                                <a href="{{ $baseUrl . $user->pad_agreement_path }}" target="_blank" title="View PAD">
                                    <i class="bi bi-file-earmark-text" style="font-size: 1.5rem;"></i>
                                </a>
                            @else
                                <span>-</span>
                            @endif
                        </td>

                        <td class="d-flex align-items-center gap-2">
                            <a href="{{ route('admin.user.edit', $user->Id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a data-bs-toggle="modal" data-bs-target="#viewBankDetails"
                                wire:click="loadDetails('{{ $user->AptPayToken }}', '{{ $user->Id }}')"
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-wallet"></i>
                            </a>
                        </td>
                    </tr>
                    <tr x-show="openRowId === '{{ $user->Id }}'" x-transition.opacity.duration.500ms style="display: none;">

    <td colspan="12" class="bg-dark-subtle border-top">
        <div class="card mb-0 border-0">
            <div class="card-header bg-transparent py-2 px-3">
                <h6 class="card-title text-muted mb-0">Latest Transactions</h6>
            </div>
            <div class="card-body pt-3 pb-2 px-3">
                <template x-if="isLoading('{{ $user->Id }}')">
                    <div>Loading...</div> <!-- You can replace with spinner if available -->
                </template>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped verticle-middle table-responsive-sm mb-0">
                            <thead class="text-uppercase small text-muted">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Direction</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody>
     @if (!empty($transactions[$user->Id]))
   
        <tr x-show="openRowId === '{{ $user->Id }}'">
                    @foreach ($transactions[$user->Id] as $txn)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($txn['CreatedAt'])->format('jS F Y') }}</td>
                            <td>
                                {{ 
                                    $txn['TransactionType'] == 1 ? 'Wire' :
                                    ($txn['TransactionType'] == 2 ? 'Interac' :
                                    ($txn['TransactionType'] == 3 ? 'Manual Withdrawal' :
                                    ($txn['TransactionType'] == 4 ? 'Credit Card' :
                                    ($txn['TransactionType'] == 5 ? 'Manual Wire' :
                                    ($txn['TransactionType'] == 6 ? 'Manual EFT' : 'Unknown')))))
                                }}

                            </td>
                            <td>
                                {{ $txn['PaymentDirection'] == 1 ? 'Collection' : ($txn['PaymentDirection'] == 3 ? 'Withdrawal' : 'Unknown') }}
                            </td>
                            <td>{{ $txn['Amount'] }} CAD</td>
                            <td>
                                @switch($txn['Status'])
                                    @case(9)
                                        <span class="badge bg-danger">Failed</span>
                                        @break
                                    @case(17)
                                        <span class="badge bg-danger">Error</span>
                                        @break
                                    @case(4)
                                        <span class="badge badge-yellow">Processing</span>
                                        @break
                                    @case(7)
                                        <span class="badge bg-success">Completed</span>
                                        @break
                                    @case(3)
                                        <span class="badge bg-warning">Pending</span>
                                        @break
                                    @case(10)
                                        <span class="badge bg-blue">Received</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Unknown</span>
                                @endswitch
                            </td>
                            <td>{{ $txn['ReferenceNumber'] }}</td>
                        </tr>
                    @endforeach
        </tr>
    @else
    <tr>
            <td colspan="6" class="text-center text-muted">No transactions found.</td>
        </tr>
    @endif
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>
        </div>
    </td>
</tr>


        
                @endforeach
            </tbody>
        </table>
        
    </div>

    <!-- Pagination & Per Page Control -->
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

        <div class="mt-4 pagination-wrapper">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Result count -->
    <div class="mt-2 text-muted small">
        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
    </div>

<!-- Modal -->
<div class="modal fade" id="viewBankDetails" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Bank Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
<div>
    <!-- Loading Spinner -->
    <div wire:loading wire:target="loadDetails,resetBankDetails" class="text-center my-3">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>


    @if (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>

    @endif
    @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
@if (session()->has('notice'))
        <div class="alert alert-warning">{{ session('notice') }}</div>
    @endif


    <form wire:loading.remove>
        {{-- Section: Account Information --}}
        <h5 class="mb-3">Account Information</h5>
        <div class="row">
            <div class="mb-3 col-md-4">
                <label>Account Title</label>
                <input type="text" class="form-control" wire:model="account_title" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Account Type</label>
                <input type="text" class="form-control" wire:model="account_type" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Transit Number</label>
                <input type="text" class="form-control" wire:model="transit_number" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Institution Number</label>
                <input type="text" class="form-control" wire:model="institution_number" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Account Number</label>
                <input type="text" class="form-control" wire:model="account_number" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Currency</label>
                <input type="text" class="form-control" wire:model="currency" readonly>
            </div>
        </div>

        {{-- Section: Balance Information --}}
        <h5 class="mb-3">Balance Information</h5>
        <div class="row">
            <div class="mb-3 col-md-4">
                <label>Available Balance</label>
                <input type="text" class="form-control" wire:model="available_balance" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Current Balance</label>
                <input type="text" class="form-control" wire:model="current_balance" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Overdraft Limit</label>
                <input type="text" class="form-control" wire:model="overdraft_limit" readonly>
            </div>
        </div>

        {{-- Section: Account Holder Information --}}
        <h5 class="mb-3">Account Holder Information</h5>
        <div class="row">
            <div class="mb-3 col-md-4">
                <label>Holder Name</label>
                <input type="text" class="form-control" wire:model="holder_name" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Email</label>
                <input type="email" class="form-control" wire:model="email" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Phone Number</label>
                <input type="text" class="form-control" wire:model="phone_number" readonly>
            </div>
        </div>

        {{-- Section: Address Information --}}
        <h5 class="mb-3">Address Information</h5>
        <div class="row">
            <div class="mb-3 col-md-6">
                <label>Street Address</label>
                <input type="text" class="form-control" wire:model="street_address" readonly>
            </div>
            <div class="mb-3 col-md-6">
                <label>City</label>
                <input type="text" class="form-control" wire:model="city" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Province</label>
                <input type="text" class="form-control" wire:model="province" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Postal Code</label>
                <input type="text" class="form-control" wire:model="postal_code" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Country</label>
                <input type="text" class="form-control" wire:model="country" readonly>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end mt-4">
            <button type="button" wire:click="resetBankDetails" class="btn btn-danger me-2">Reset Bank Details</button>
            {{-- <button
    type="button"
    wire:click="toggleManualBankData"
    class="btn {{ $user->ManualBankData ? 'btn-success' : 'btn-secondary' }}"
>
    {{ $user->ManualBankData ? 'Manual Bank Data: ON' : 'Manual Bank Data: OFF' }}
</button> --}}

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </form>
    
</div>            </div>
        </div>
    </div>
</div>
<script>
window.addEventListener('brand-added-success', event => {
    alert('Brand added successfully!');
});
</script>
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            // Optional: show a success toast or alert
            console.log('Copied:', text);
        }).catch(err => {
            console.error('Failed to copy:', err);
        });
    }
</script>

<script>
    window.addEventListener('close-modal', () => {
const modal = bootstrap.Modal.getInstance(document.getElementById('viewBankDetails'));
modal.hide();
});
</script>

<script>
function initUserManagementMultiSelects() {
    if (!window.jQuery) {
        return;
    }

    const statusSelect = window.jQuery('#user-status-filter');
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

    const brandSelect = window.jQuery('#user-brand-filter');
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
            case '1': // New
                inner.classList.add('badge-secondary');
                break;
            case '2': // Pending
                inner.classList.add('badge-warning');
                break;
            case '3': // Review
                inner.classList.add('badge-info');
                break;
            case '4': // Escalated
                inner.classList.add('badge-dark');
                break;
            case '5': // Accepted
                inner.classList.add('badge-primary');
                break;
            case '6': // Rejected
                inner.classList.add('badge-danger');
                break;
            case '7': // Expired
                inner.classList.add('badge-light');
                break;
            case '8': // Approved
                inner.classList.add('badge-success');
                break;
            case '9': // None
                inner.classList.add('badge-muted');
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
}, 300); // 300ms is usually sufficient


            el.classList.add('choices-initialized');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initChoicesDropdowns();
    initUserManagementMultiSelects();
});

 window.addEventListener('livewire-updated', () => {
        // Re-initialize Choices.js for dynamic status dropdowns
        setTimeout(() => {
            initChoicesDropdowns();
            initUserManagementMultiSelects();
        }, 300); // Short delay to ensure DOM is ready
    });

</script>
</div>
</div>
