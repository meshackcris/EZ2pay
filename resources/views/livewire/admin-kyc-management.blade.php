<div x-data="{ tab: 'pending', showFilters: false }">

    <h4 class="mb-4">KYC Submissions Management</h4>
    <div wire:loading.delay class="text-center my-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- Tabs -->
    <div class="input-group mb-4">
        <button @click="tab = 'pending'" 
                class="btn btn-primary" 
                :class="tab === 'pending' ? 'btn-dark' : 'btn-outline-dark'">
            Pending ({{ $pendingKycs->total() }})
        </button>
        <button @click="tab = 'approved'" 
                class="btn btn-primary" 
                :class="tab === 'approved' ? 'btn-dark' : 'btn-outline-dark'">
            Approved ({{ $approvedKycs->total() }})
        </button>
        <button @click="tab = 'rejected'" 
                class="btn btn-primary" 
                :class="tab === 'rejected' ? 'btn-dark' : 'btn-outline-dark'">
            Rejected ({{ $rejectedKycs->total() }})
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
        <div class="row g-2">
            <div class="col-md-3">
                <label>Date From</label>
                <input type="date" wire:model="startDate" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Date To</label>
                <input type="date" wire:model="endDate" class="form-control">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button wire:click="clearFilters" class="btn btn-outline-danger btn-xs me-2">Clear</button>
                <button wire:click="$refresh" class="btn btn-outline-success btn-xs">Apply</button>
            </div>
        </div>
    </div>

    <!-- Pending Table -->
    <div x-show="tab === 'pending'" class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Submission Date</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Brand</th>
                    <th>ID Type</th>
                    <th>Document Front</th>
                    <th>Document Back</th>
                    <th>Proof of Address</th>
                    <th>Video</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingKycs as $kyc)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($kyc->submitted_at)->format('Y-m-d H:i') }}</td>
                        <td>{{ optional($kyc->user)->FirstName }} {{ optional($kyc->user)->LastName }}</td>
                        <td>{{ optional($kyc->user)->Email }}</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                        <td>{{ optional($kyc->brand)->brand_name }}</td>
                        @php
    $baseUrl = 'https://orion-pay.tor1.cdn.digitaloceanspaces.com/';
@endphp

<td>{{ $kyc->identification_type }}</td>

<td>
    <a href="{{ $baseUrl . $kyc->document_front_path }}" target="_blank">View</a>
</td>

<td>
    @if ($kyc->document_back_path)
        <a href="{{ $baseUrl . $kyc->document_back_path }}" target="_blank">View</a>
    @else
        N/A
    @endif
</td>

<td>
    <a href="{{ $baseUrl . $kyc->poa_path }}" target="_blank">View</a>
</td>

<td>
    <a href="{{ $baseUrl . 'kyc_videos/' . $kyc->video_path }}" target="_blank">View Video</a>
</td>

                        <td>
                            <button wire:click="approve('{{ $kyc->id }}')" class="btn btn-success btn-sm">Approve</button>
                            <button wire:click="reject('{{ $kyc->id }}')" class="btn btn-danger btn-sm">Reject</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted">No pending KYC submissions.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $pendingKycs->links() }}</div>
    </div>
    

    <!-- Approved Table -->
    <div x-show="tab === 'approved'" class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Submission Date</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Brand</th>
                    <th>ID Type</th>
                    <th>Document Front</th>
                    <th>Document Back</th>
                    <th>Proof of Address</th>
                    <th>Video</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($approvedKycs as $kyc)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($kyc->submitted_at)->format('Y-m-d H:i') }}</td>
                        <td>{{ optional($kyc->user)->FirstName }} {{ optional($kyc->user)->LastName }}</td>
                        <td>{{ optional($kyc->user)->Email }}</td>
                        <td><span class="badge bg-success">Approved</span></td>
                        <td>{{ optional($kyc->brand)->brand_name }}</td>
                        <td>{{ $kyc->identification_type }}</td>
@php
    $baseUrl = 'https://orion-pay.tor1.cdn.digitaloceanspaces.com/';
@endphp

<td>
    <a href="{{ $baseUrl . $kyc->document_front_path }}" target="_blank">View</a>
</td>

<td>
    @if ($kyc->document_back_path)
        <a href="{{ $baseUrl . $kyc->document_back_path }}" target="_blank">View</a>
    @else
        N/A
    @endif
</td>

<td>
    <a href="{{ $baseUrl . $kyc->poa_path }}" target="_blank">View</a>
</td>

<td>
    <a href="{{ $baseUrl . 'kyc_videos/' . $kyc->video_path }}" target="_blank">View Video</a>
</td>

                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">No approved KYC submissions.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $approvedKycs->links() }}</div>
    </div>
        <!-- Rejected Table -->

    <div x-show="tab === 'rejected'" class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Submission Date</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Brand</th>
                    <th>ID Type</th>
                    <th>Document Front</th>
                    <th>Document Back</th>
                    <th>Proof of Address</th>
                    <th>Video</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rejectedKycs as $kyc)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($kyc->submitted_at)->format('Y-m-d H:i') }}</td>
                        <td>{{ optional($kyc->user)->FirstName }} {{ optional($kyc->user)->LastName }}</td>
                        <td>{{ optional($kyc->user)->Email }}</td>
                        <td><span class="badge bg-danger">Rejected</span></td>
                        <td>{{ optional($kyc->brand)->brand_name }}</td>
                        <td>{{ $kyc->identification_type }}</td>
@php
    $baseUrl = 'https://orion-pay.tor1.cdn.digitaloceanspaces.com/';
@endphp

<td>
    <a href="{{ $baseUrl . $kyc->document_front_path }}" target="_blank">View</a>
</td>

<td>
    @if ($kyc->document_back_path)
        <a href="{{ $baseUrl . $kyc->document_back_path }}" target="_blank">View</a>
    @else
        N/A
    @endif
</td>

<td>
    <a href="{{ $baseUrl . $kyc->poa_path }}" target="_blank">View</a>
</td>

<td>
    <a href="{{ $baseUrl . 'kyc_videos/' . $kyc->video_path }}" target="_blank">View Video</a>
</td>

                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">No rejected KYC submissions.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $rejectedKycs->links() }}</div>
    </div>
</div>
