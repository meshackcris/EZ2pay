<div x-data="{ tab: 'pending', showFilters: false }"
     x-on:switch-to-approved.window="
        tab = 'approved';
        $nextTick(() => {
            const approveModal = bootstrap.Modal.getInstance(document.getElementById('approveModal'));
            if (approveModal) approveModal.hide();
        });
     "
     x-on:switch-to-rejected.window="
        tab = 'rejected';
        $nextTick(() => {
            const rejectModal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
            if (rejectModal) rejectModal.hide();
        });
     "
      x-on:close-modal.window="
        $nextTick(() => {
            document.querySelectorAll('.modal.show').forEach(el => {
                const instance = bootstrap.Modal.getInstance(el);
                if (instance) instance.hide();
            });
        });
     "
>
@once
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endonce
<div class="row">
                            <div class="col-xl-3 col-sm-6">
                                <div class="card overflow-hidden">
                                    <div class="card-header border-0">
                                        <div class="d-flex">
                                            <span class="mt-2 dash">
                                                <svg width="32" height="40" viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.812 34.64L3.2 39.6C2.594 40.054 1.784 40.128 1.106 39.788C0.428 39.45 0 38.758 0 38V2C0 0.896 0.896 0 2 0H30C31.104 0 32 0.896 32 2V38C32 38.758 31.572 39.45 30.894 39.788C30.216 40.128 29.406 40.054 28.8 39.6L22.188 34.64L17.414 39.414C16.634 40.196 15.366 40.196 14.586 39.414L9.812 34.64ZM28 34V4H4V34L8.8 30.4C9.596 29.802 10.71 29.882 11.414 30.586L16 35.172L20.586 30.586C21.29 29.882 22.404 29.802 23.2 30.4L28 34ZM14 20H18C19.104 20 20 19.104 20 18C20 16.896 19.104 16 18 16H14C12.896 16 12 16.896 12 18C12 19.104 12.896 20 14 20ZM10 12H22C23.104 12 24 11.104 24 10C24 8.896 23.104 8 22 8H10C8.896 8 8 8.896 8 10C8 11.104 8.896 12 10 12Z" fill="#717579"/>
                                                </svg>
                                            </span>
                                            <div class="invoices">
                                                <h4>${{ number_format($pendingTotal,2) }}</h4>
                                                <span>Pending Withdrawals</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                            
                                        <div id="totalInvoices"></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card overflow-hidden">
                                    <div class="card-header border-0">
                                        <div class="d-flex">
                                            <span class="mt-1 dashs">
                                                <svg width="58" height="58" viewBox="0 0 58 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.812 48.64L11.2 53.6C10.594 54.054 9.78401 54.128 9.10602 53.788C8.42802 53.45 8.00002 52.758 8.00002 52V16C8.00002 14.896 8.89602 14 10 14H38C39.104 14 40 14.896 40 16V52C40 52.758 39.572 53.45 38.894 53.788C38.216 54.128 37.406 54.054 36.8 53.6L30.188 48.64L25.414 53.414C24.634 54.196 23.366 54.196 22.586 53.414L17.812 48.64ZM36 48V18H12V48L16.8 44.4C17.596 43.802 18.71 43.882 19.414 44.586L24 49.172L28.586 44.586C29.29 43.882 30.404 43.802 31.2 44.4L36 48ZM22 34H26C27.104 34 28 33.104 28 32C28 30.896 27.104 30 26 30H22C20.896 30 20 30.896 20 32C20 33.104 20.896 34 22 34ZM18 26H30C31.104 26 32 25.104 32 24C32 22.896 31.104 22 30 22H18C16.896 22 16 22.896 16 24C16 25.104 16.896 26 18 26Z" fill="#44814E"/>
                                                    <circle cx="43.5" cy="14.5" r="12.5" fill="#09BD3C" stroke="white" stroke-width="4"/>
                                                </svg>
                                            </span>
                                            <div class="invoices">
                                                <h4>${{ number_format($approvedTotal,2) }}</h4>
                                                <span>Approved Withdrawals</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                            
                                        <div id="paidinvoices"></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card overflow-hidden">
                                    <div class="card-header border-0">
                                        <div class="d-flex">
                                            <span class="mt-1 dashs">
                                                <svg width="58" height="58" viewBox="0 0 58 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.812 48.64L11.2 53.6C10.594 54.054 9.78401 54.128 9.10602 53.788C8.42802 53.45 8.00002 52.758 8.00002 52V16C8.00002 14.896 8.89602 14 10 14H38C39.104 14 40 14.896 40 16V52C40 52.758 39.572 53.45 38.894 53.788C38.216 54.128 37.406 54.054 36.8 53.6L30.188 48.64L25.414 53.414C24.634 54.196 23.366 54.196 22.586 53.414L17.812 48.64ZM36 48V18H12V48L16.8 44.4C17.596 43.802 18.71 43.882 19.414 44.586L24 49.172L28.586 44.586C29.29 43.882 30.404 43.802 31.2 44.4L36 48ZM22 34H26C27.104 34 28 33.104 28 32C28 30.896 27.104 30 26 30H22C20.896 30 20 30.896 20 32C20 33.104 20.896 34 22 34ZM18 26H30C31.104 26 32 25.104 32 24C32 22.896 31.104 22 30 22H18C16.896 22 16 22.896 16 24C16 25.104 16.896 26 18 26Z" fill="#44814E"/>
                                                    <circle cx="43.5" cy="14.5" r="12.5" fill="#FD5353" stroke="white" stroke-width="4"/>
                                                </svg>

                                            </span>
                                            <div class="invoices">
                                                <h4>${{ number_format($rejectedTotal,2) }}</h4>
                                                <span>Rejected Withdrawals</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="unpaidinvoices"></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card overflow-hidden">
                                    <div class="card-header border-0">
                                        <div class="d-flex">
                                            <span class="mt-1 dashs">
                                                <svg width="58" height="58" viewBox="0 0 58 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.812 48.64L11.2 53.6C10.594 54.054 9.784 54.128 9.106 53.788C8.428 53.45 8 52.758 8 52V16C8 14.896 8.896 14 10 14H38C39.104 14 40 14.896 40 16V52C40 52.758 39.572 53.45 38.894 53.788C38.216 54.128 37.406 54.054 36.8 53.6L30.188 48.64L25.414 53.414C24.634 54.196 23.366 54.196 22.586 53.414L17.812 48.64ZM36 48V18H12V48L16.8 44.4C17.596 43.802 18.71 43.882 19.414 44.586L24 49.172L28.586 44.586C29.29 43.882 30.404 43.802 31.2 44.4L36 48ZM22 34H26C27.104 34 28 33.104 28 32C28 30.896 27.104 30 26 30H22C20.896 30 20 30.896 20 32C20 33.104 20.896 34 22 34ZM18 26H30C31.104 26 32 25.104 32 24C32 22.896 31.104 22 30 22H18C16.896 22 16 22.896 16 24C16 25.104 16.896 26 18 26Z" fill="#44814E"/>
                                                    <circle cx="43.5" cy="14.5" r="12.5" fill="#FFAA2B" stroke="white" stroke-width="4"/>
                                                </svg>


                                            </span>
                                            <div class="invoices">
                                                <h4>${{ number_format($totalWithdrawal,2) }}</h4>
                                                <span>Total Withdrawals</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="totalinvoicessent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
    <!-- Loading Spinner -->
    <div wire:loading.delay class="text-center my-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Tabs -->
    <div class="input-group mb-4">
        <button @click="tab = 'pending'" class="btn btn-primary" :class="tab === 'pending' ? 'btn-dark' : 'btn-outline-dark'">
            Pending ({{ $pendingWithdrawals->total() }})
        </button>
        <button @click="tab = 'approved'" class="btn btn-primary" :class="tab === 'approved' ? 'btn-dark' : 'btn-outline-dark'">
            Approved ({{ $approvedWithdrawals->total() }})
        </button>
        <button @click="tab = 'rejected'" class="btn btn-primary" :class="tab === 'rejected' ? 'btn-dark' : 'btn-outline-dark'">
            Rejected ({{ $rejectedWithdrawals->total() }})
        </button>
    </div>

    <!-- Filters & Search -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Search by user..." style="max-width: 250px;">
        <div class="d-flex gap-2">
            <button @click="showFilters = !showFilters" class="btn btn-outline-primary btn-xs">
                <i class="fas fa-filter"></i>
            </button>
            <button wire:click="$refresh" class="btn btn-outline-primary btn-xs">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <!-- Filters Panel -->
    <div x-show="showFilters" x-transition.duration.200ms class="p-3 rounded bg-light-subtle border mb-4">
        @php
            $statusOptions = [
                ['value' => 3, 'label' => 'Pending'],
                ['value' => 4, 'label' => 'Processing'],
                ['value' => 7, 'label' => 'Approved'],
                ['value' => 10, 'label' => 'Received'],
                ['value' => 9, 'label' => 'Rejected'],
            ];
        @endphp
        <div class="row g-2">
            <div class="col-md-3">
                <label>Date From</label>
                <input type="date" wire:model="startDate" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Date To</label>
                <input type="date" wire:model="endDate" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Status</label>
                <select wire:model="status" id="withdrawal-status-filter" class="form-select multi-select" multiple="multiple" data-placeholder="Select status" style="width: 100%;">
                    @foreach ($statusOptions as $option)
                        <option value="{{ $option['value'] }}" @selected(in_array($option['value'], $status ?? []))>
                            {{ $option['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button wire:click="clearFilters" class="btn btn-outline-danger btn-xs me-2">Clear</button>
                <button wire:click="$refresh" class="btn btn-outline-success btn-xs">Apply</button>
            </div>
        </div>
    </div>

    <!-- Pending Withdrawals Table -->
    <div x-show="tab === 'pending'" class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Request Date</th>
                    <th>Ref</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Brand</th>
                    <th>Amount</th>
                    <th>Crypto</th>
                    <th>Chain</th>
                    <th>Wallet</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Fees</th>
                    <th>Rate</th>
                    <th>Final Amount (USD)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingWithdrawals as $withdrawal)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($withdrawal->CreatedAt)->format('Y-m-d H:i') }}</td>
                        <td>{{ $withdrawal->ReferenceNumber}}</td>
                        <td>{{ optional($withdrawal->legacyUser)->FirstName }} {{ optional($withdrawal->legacyUser)->LastName }}</td>
                        <td>{{ optional($withdrawal->legacyUser)->Email }}</td>
                        <td>{{ optional($withdrawal->brand)->brand_name }}</td>
                        <td>${{ number_format($withdrawal->Amount, 2) }}</td>
                        <td>{{ $withdrawal->CryptoCurrency }}</td>
                        <td>{{ $withdrawal->Chain }}</td>
                        <td>{{ $withdrawal->WalletAddress }}</td>
                        @php
                            $types = [
                                1 => 'EFT',
                                2 => 'Interac',
                                3 => 'Withdrawal',
                                4 => 'Credit Card',
                                5 => 'Manual Wire',
                                6 => 'Manual EFT',
                                7 => 'Interac FTD',
                            ];
                        @endphp

                        <td>{{ $types[$withdrawal->deposit->TransactionType ?? null] ?? 'N/A' }}</td>
                        <td>
                            @if ($withdrawal->Status == 3)
                                <span class="badge bg-warning">Pending</span>
                            @elseif ($withdrawal->Status == 4)
                                <span class="badge badge-yellow">Processing</span>
                            @endif
                        </td>
                        <td>{{ $withdrawal->Fees ?? 'N/A' }}%</td>
                        <td>{{ $withdrawal->Rate ?? 'N/A' }}</td>
                        <td>
                            @if($withdrawal->FinalAmount)
                                ${{ number_format($withdrawal->FinalAmount, 2) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal" data-id="{{ $withdrawal->Id }}">
                                Approve
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="{{ $withdrawal->Id }}">
                                Reject
                            </button>
                            <button wire:click="loadFees('{{$withdrawal->Id}}')" type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $withdrawal->Id }}">
                                Edit
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted">No pending withdrawals.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $pendingWithdrawals->links() }}</div>
    </div>

    <!-- Approved Withdrawals Table -->
    <div x-show="tab === 'approved'" class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Approval Date</th>
                    <th>Ref</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Brand</th>
                    <th>Amount</th>
                    <th>Crypto</th>
                    <th>Chain</th>
                    <th>Wallet</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Fees</th>
                    <th>Rate</th>
                    <th>Final Amount (USD)</th>
                    <th>Transaction Hash</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($approvedWithdrawals as $withdrawal)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($withdrawal->updated_at)->format('Y-m-d H:i') }}</td>
                        <td>{{ $withdrawal->ReferenceNumber}}</td>
                        <td>{{ optional($withdrawal->legacyUser)->FirstName }} {{ optional($withdrawal->legacyUser)->LastName }}</td>
                        <td>{{ optional($withdrawal->legacyUser)->Email }}</td>
                        <td>{{ optional($withdrawal->brand)->brand_name }}</td>
                        <td>${{ number_format($withdrawal->Amount, 2) }}</td>
                        <td>{{ $withdrawal->CryptoCurrency }}</td>
                        <td>{{ $withdrawal->Chain }}</td>
                        <td>{{ $withdrawal->WalletAddress }}</td>
                        @php
                            $types = [
                                1 => 'EFT',
                                2 => 'Interac',
                                3 => 'Withdrawal',
                                4 => 'Credit Card',
                                5 => 'Manual Wire',
                                6 => 'Manual EFT',
                                7 => 'Interac FTD',
                            ];
                        @endphp
                        <td>{{ $types[$withdrawal->deposit->TransactionType ?? null] ?? 'N/A' }}</td>
<td>
    <span class="badge {{ $withdrawal->Status == 10 ? 'bg-blue' : 'bg-success' }}">
        {{ $withdrawal->Status == 10 ? 'Received' : 'Approved' }}
    </span>
</td>
                        <td>{{ $withdrawal->Fees ?? 'N/A' }}%</td>
                        <td>{{ $withdrawal->Rate ?? 'N/A' }}</td>
                        <td>
                            @if($withdrawal->FinalAmount)
                                ${{ number_format($withdrawal->FinalAmount, 2) }}
                            @else
                                N/A
                            @endif  
                        </td>
                        <td>{{ $withdrawal->TransactionHash }}</td>
                        <td>
                            <button wire:click="loadFees('{{$withdrawal->Id}}')" type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $withdrawal->Id }}">
                                Edit
                            </button></td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted">No approved withdrawals.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $approvedWithdrawals->links() }}</div>
    </div>
<!-- Rejected Withdrawals Table -->
    <div x-show="tab === 'rejected'" class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Rejected Date</th>
                    <th>Ref</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Brand</th>
                    <th>Amount</th>
                    <th>Crypto</th>
                    <th>Chain</th>
                    <th>Wallet</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Fees</th>
                    <th>Rate</th>
                    <th>Final Amount (USD)</th>
                    <th>Reason</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rejectedWithdrawals as $withdrawal)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($withdrawal->updated_at)->format('Y-m-d H:i') }}</td>
                        <td>{{ $withdrawal->ReferenceNumber}}</td>
                        <td>{{ optional($withdrawal->legacyUser)->FirstName }} {{ optional($withdrawal->legacyUser)->LastName }}</td>
                        <td>{{ optional($withdrawal->legacyUser)->Email }}</td>
                        <td>{{ optional($withdrawal->brand)->brand_name }}</td>
                        <td>${{ number_format($withdrawal->Amount, 2) }}</td>
                        <td>{{ $withdrawal->CryptoCurrency }}</td>
                        <td>{{ $withdrawal->Chain }}</td>
                        <td>{{ $withdrawal->WalletAddress }}</td>
                        @php
                            $types = [
                                1 => 'EFT',
                                2 => 'Interac',
                                3 => 'Withdrawal',
                                4 => 'Credit Card',
                                5 => 'Manual Wire',
                                6 => 'Manual EFT',
                                7 => 'Interac FTD',
                            ];
                        @endphp
                        <td>{{ $types[$withdrawal->deposit->TransactionType ?? null] ?? 'N/A' }}</td>
                        <td><span class="badge bg-danger">Rejected</span></td>
                        <td>{{ $withdrawal->Fees ?? 'N/A' }}%</td>
                        <td>{{ $withdrawal->Rate ?? 'N/A' }}</td>
                        <td>
                            @if($withdrawal->FinalAmount)
                                ${{ number_format($withdrawal->FinalAmount, 2) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $withdrawal->ErrorMessage }}</td>
                        <td>
                            <button wire:click="loadFees('{{$withdrawal->Id}}')" type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $withdrawal->Id }}">
                                Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $withdrawal->Id }}">
                                Delete
                            </button></td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted">No rejected withdrawals.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $rejectedWithdrawals->links() }}</div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
   
            <div class="modal-content">
                <form wire:submit.prevent="approveWithdrawal">
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Withdrawal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <!-- Loading Spinner -->
    <div wire:loading.delay class="text-center my-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" wire:model="selectedWithdrawalId" id="approve_withdrawal_id">
                        <label>Transaction Hash</label>
                        <input type="text" wire:model="transactionHash" class="form-control" placeholder="Enter Transaction Hash">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
   
            <div class="modal-content">
                <form wire:submit.prevent="deleteWithdrawal">
                    <div class="modal-header">
                        <h5 class="modal-title">delete Withdrawal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <!-- Loading Spinner -->
    <div wire:loading.delay class="text-center my-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" wire:model="selectedWithdrawalId" id="delete_withdrawal_id">
                        <p>Are you sure you want to delete this withdrawal record? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            
            <div class="modal-content">
                <form wire:submit.prevent="rejectWithdrawal">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Withdrawal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- Loading Spinner -->
    <div wire:loading.delay class="text-center my-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
                    <div class="modal-body">
                        <input type="hidden" wire:model="selectedWithdrawalId" id="reject_withdrawal_id">
                        <label>Rejection Reason</label>
                        <textarea wire:model="rejectionReason" class="form-control" placeholder="Enter reason"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            
            <div class="modal-content">
                <form wire:submit.prevent="editWithdrawal">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Withdrawal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- Loading Spinner -->
    <div wire:loading.delay class="text-center my-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                        <input type="hidden" wire:model="selectedWithdrawalId" id="edit_withdrawal_id">
                        <label>Transaction Hash</label>
                        <input type="text" wire:model="transactionHash" class="form-control" placeholder="Hash"></input>
                        </div>
                        
                        <div class="mb-3">
                        <label>Brand Fees</label>
                        <input type="number" wire:model="fees" class="form-control" placeholder="Fees" min="0" step="any"></input>
                        </div>
                    
                        <div class="mb-3">
                         <label>Rate</label>
                        <input type="number" wire:model="rate" class="form-control" placeholder="Rate" min="0" step="any" required></input>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Save</button>
                    </div>
                </form>
            </div>
        </div>
</div>

    <!-- JavaScript to Populate Hidden Fields -->
@once
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endonce
@once
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endonce
<script>
    function initWithdrawalStatusSelect() {
        if (!window.jQuery) {
            return;
        }

        const statusSelect = window.jQuery('#withdrawal-status-filter');
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
        initWithdrawalStatusSelect();
    });

    window.addEventListener('livewire-updated', () => {
        setTimeout(() => {
            initWithdrawalStatusSelect();
        }, 200);
    });
</script>
    <script>
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const withdrawalId = button.getAttribute('data-id');
        const input = document.getElementById('delete_withdrawal_id');
        input.value = withdrawalId;
        input.dispatchEvent(new Event('input')); // 🟢 Trigger Livewire awareness
    });

    const approveModal = document.getElementById('approveModal');
    approveModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const withdrawalId = button.getAttribute('data-id');
        const input = document.getElementById('approve_withdrawal_id');
        input.value = withdrawalId;
        input.dispatchEvent(new Event('input')); // 🟢 Trigger Livewire awareness
    });

    const rejectModal = document.getElementById('rejectModal');
    rejectModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const withdrawalId = button.getAttribute('data-id');
        const input = document.getElementById('reject_withdrawal_id');
        input.value = withdrawalId;
        input.dispatchEvent(new Event('input')); // 🟢 Trigger Livewire awareness
    });

    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const withdrawalId = button.getAttribute('data-id');
        const input = document.getElementById('edit_withdrawal_id');
        input.value = withdrawalId;
        input.dispatchEvent(new Event('input')); // 🟢 Trigger Livewire awareness
    });
</script>



</div>
