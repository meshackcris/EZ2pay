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
    <div class="col-xl-12 col-lg-12">
        <div class="row">
            <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit User</h4>
            </div>
            <div class="card-body">
                <div wire:loading.delay class="text-center my-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div class="basic-form">
                    @if (session()->has('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

                    <form wire:submit.prevent="saveUser">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">User ID</label>
                                <input type="text" wire:model.defer="UserId" class="form-control" readonly>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" wire:model.defer="Email" class="form-control" placeholder="Enter Email">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" wire:model.defer="FirstName" class="form-control" placeholder="Enter First Name">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" wire:model.defer="LastName" class="form-control" placeholder="Enter Last Name">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" wire:model.defer="PhoneNumber" class="form-control" placeholder="Enter Phone Number">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Verification Status</label>
                                <select wire:model.defer="VerificationStatus" class="default-select form-control wide">
                                    <option value="1">New</option>
                                    <option value="2">Pending</option>
                                    <option value="3">Review</option>
                                    <option value="4">Escalated</option>
                                    <option value="5">Accepted</option>
                                    <option value="6">Rejected</option>
                                    <option value="7">Expired</option>
                                    <option value="8">Approved</option>
                                    <option value="9">None</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Brand</label>
                                <select wire:model.defer="Brand_Id" class="form-select">
                                    <option value="" >Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            
                            <div class="mb-3 col-md-6">
                                <label class="form-label">IP Address</label>
                                <input type="text" wire:model.defer="IpAddress" class="form-control" placeholder="Enter IP Address" readonly>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" wire:model.defer="Password" class="form-control" placeholder="Enter New Password">
                            </div>
                            <div class="mb-3 col-md-6">
                                    <label class="form-label">Pad Agreement</label>
                                    <select wire:model.defer="padAgreement" class="default-select form-control wide">
                                    <option value="0">Not Uploaded</option>
                                    <option value="1">Uploaded</option>                            
                                    <option value="2">Reset</option>                            
                                    <option value="3">Skip</option>   
                                    </select>                         
                                </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                                <button type="button" class="btn btn-danger"
                                    onclick="if (confirm('Are you sure you want to delete this user?')) { @this.confirmDelete() }">
                                    Delete User
                                </button>
                                <button type="button" class="btn btn-primary me-2" onclick="showKyc('{{ $UserId }}')" >Upload Kyc</button>
                                <button type="button" class="btn btn-primary me-2" wire:click="resetKyc" >Reset Kyc</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"></h4>
            </div>
            <div class="card-body">
                

                <div class="basic-form">
                   

                <button type="button" class="btn btn-primary me-2" wire:click="resetPOI" >Delete POI</button>
                <button type="button" class="btn btn-primary me-2" wire:click="resetPOR" >Delete POR</button>
                <button type="button" class="btn btn-primary me-2" wire:click="resetKycVid" >Delete Video</button>
                <button type="button" class="btn btn-primary me-2" wire:click="resetWallet" >Reset Wallet</button>

                </div>
            </div>
        </div>
        </div>
        </div>
    </div>

    <hr class="my-4 border-secondary-subtle">

    <div class="card">
        <div class="bg-light-subtle p-3 rounded shadow-sm">
        <div class="mb-3 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-muted">User Transactions</h5>
            </div>
        <div wire:loading.delay class="text-center my-3">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div x-data="{ showFilters: false }">
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
                            id="edit-user-status-filter"
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
                            id="edit-user-direction-filter"
                            class="form-select multi-select"
                            multiple="multiple"
                            data-placeholder="Select direction"
                            style="width: 100%;"
                        >
                            <option value="1" @selected(in_array(1, $direction ?? []))>Collection</option>
                            <option value="0" @selected(in_array(0, $direction ?? []))>Disbursement</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Crypto</label>
                        <select
                            wire:model="cryptoCurrency"
                            id="edit-user-crypto-filter"
                            class="form-select multi-select"
                            multiple="multiple"
                            data-placeholder="Select crypto"
                            style="width: 100%;"
                        >
                            <option value="BTC" @selected(in_array('BTC', $cryptoCurrency ?? []))>BTC</option>
                            <option value="ETH" @selected(in_array('ETH', $cryptoCurrency ?? []))>ETH</option>
                            <option value="CAD" @selected(in_array('CAD', $cryptoCurrency ?? []))>CAD</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Type</label>
                        <select
                            wire:model="transactionType"
                            id="edit-user-type-filter"
                            class="form-select multi-select"
                            multiple="multiple"
                            data-placeholder="Select type"
                            style="width: 100%;"
                        >
                            <option value="1" @selected(in_array(1, $transactionType ?? []))>Wire</option>
                            <option value="2" @selected(in_array(2, $transactionType ?? []))>Interact</option>
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
                                        10 => ['label' => 'Received', 'class' => 'badge bg-blue'],
                                    ];
                                    $status = $statusLabels[$txn->Status] ?? ['label' => 'Unknown', 'class' => 'badge-secondary'];
                                @endphp
                                <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                            </td>
                            <td>{{ $txn->ReferenceNumber }}</td>
                            <td>{{ $txn->legacyUser?->FullName ?? 'Unknown User' }}</td>
                            <td>
                                @php
                                    $direction = $txn->PaymentDirection == 0 ? 'Disbursement' : 'Collection';
                                @endphp
                                {{ $direction }}
                            </td>
                            <td>${{ number_format($txn->Amount, 2) }}</td>
                            <td>{{ $txn->CryptoCurrency ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $typeMap = [1 => 'Wire', 2 => 'Interact'];
                                @endphp
                                {{ $typeMap[$txn->TransactionType] ?? 'Unknown' }}
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
    </div>
    
    <div class="modal fade" id="kycModal" tabindex="-1" aria-labelledby="kycModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" x-data="{ idType: '' }">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="kycModalLabel">Manual KYC Upload</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

<form action="{{ route('submit.kyc') }}" class="m-4" method="POST" enctype="multipart/form-data" x-data="{idType: ''}">
    @csrf

    <div class="mb-3">
        <label><strong>Identification Type</strong></label>
        <select name="identificationType" x-model="idType" class="form-select" required>
            <option value="">-- Select ID Type --</option>
            <option value="drivers_license">Driver's License</option>
            <option value="passport">International Passport</option>
            <option value="other">Other</option>
        </select>
    </div>

    <div x-show="idType === 'drivers_license'" class="mb-3">
        <label><strong>Front of Driver's License</strong></label>
        <input type="file" name="drivers_license_front" class="form-control" >
    </div>

    <div x-show="idType === 'drivers_license'" class="mb-3">
        <label><strong>Back of Driver's License</strong></label>
        <input type="file" name="drivers_license_back" class="form-control">
    </div>

    <div x-show="idType === 'passport'" class="mb-3">
        <label><strong>Passport Document</strong></label>
        <input type="file" name="passport_front" class="form-control" >
    </div>

    <div x-show="idType === 'other'" class="mb-3">
        <label><strong>Type of Identification</strong></label>
        <input type="text" name="other_id_type" class="form-control" >
    </div>

    <div x-show="idType === 'other'" class="mb-3">
        <label><strong>Front of ID</strong></label>
        <input type="file" name="other_id_front" class="form-control" >
    </div>

    <div x-show="idType === 'other'" class="mb-3">
        <label><strong>Back of ID</strong></label>
        <input type="file" name="other_id_back" class="form-control">
    </div>
    <div class="mb-3">
        <label><strong>Proof of Address</strong></label>
        <input type="file" name="proofOfAddress" class="form-control" >
    </div>
    <div class="mb-3">
        <label>Video (mp4/mov/avi/webm)</label>
        <input type="file" id="video" name="video" />

    </div>
              <input type="hidden" name="user_id" id="kycUserId">


    <button type="submit"
        class="btn btn-primary">Submit KYC</button>

                    </form>
    </div>
  </div>
</div>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

<script>
function initEditUserMultiSelects() {
    if (!window.jQuery) {
        return;
    }

    const statusSelect = window.jQuery('#edit-user-status-filter');
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

    const directionSelect = window.jQuery('#edit-user-direction-filter');
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

    const cryptoSelect = window.jQuery('#edit-user-crypto-filter');
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

    const typeSelect = window.jQuery('#edit-user-type-filter');
    if (typeSelect.length) {
        if (typeSelect.hasClass('select2-hidden-accessible')) {
            typeSelect.off('change.select2');
            typeSelect.select2('destroy');
        }

        typeSelect.select2({
            placeholder: typeSelect.data('placeholder') || '',
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
        });

        typeSelect.on('change.select2', function () {
            let val = typeSelect.val() || [];
            val = val.filter((value) => value !== '').map((value) => parseInt(value, 10));
            @this.set('transactionType', val);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initEditUserMultiSelects();
});

window.addEventListener('livewire-updated', () => {
    setTimeout(() => {
        initEditUserMultiSelects();
    }, 200);
});
</script>

<script>
    function initFilePondWhenReady() {
    const inputElement = document.getElementById('video');
    if (inputElement) {
    // Create a FilePond instance
    const pond = FilePond.create(inputElement);
    FilePond.setOptions({
    server: {
        process: '/kyc-tmp-upload',
        revert: '/kyc-tmp-delete',
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
    function showKyc(userId) {
            // Set hidden input or text in modal if needed
            const modal = new bootstrap.Modal(document.getElementById('kycModal'));
            modal.show();
                document.getElementById('kycUserId').value = userId;
    }
</script>
</div>
