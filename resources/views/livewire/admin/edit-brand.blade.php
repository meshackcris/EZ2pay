<div class="col-xl-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Brand</h4>
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

                <form wire:submit.prevent="saveBrand">
                    <div class="row">
                        <div class="col-md-4">
                    <div class="row">

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Brand Name</label>
                            <input type="text" wire:model.defer="brand_name" class="form-control" placeholder="Enter Brand Name">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" wire:model.defer="username" class="form-control" placeholder="Enter Username">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Status</label>
                            <select wire:model.defer="status" class="default-select form-control wide">
                                <option value="1">Active</option>
                                <option value="2">Suspended</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Registration Link</label>
                            <input type="text" wire:model.defer="registration_link" class="form-control" readonly>
                        </div>


                       <div class="mb-3 col-md-6" wire:ignore>
            <label>Referrer</label>
            <select id="referrer-select" class="form-control ref-select">
              <option value="">-- Select Referrer --</option>
                @foreach ($referrerList as $item)
                    <option value="{{ $item->username }}" {{ $referrer === $item->username ? 'selected' : '' }}>
                        {{ $item->username }} — {{ $item->email }}
                    </option>
                @endforeach
                </select>
            @error('referrer') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Email</label>
                            <input type="text" wire:model.defer="email" class="form-control" placeholder="Enter ">
                        </div>

                        <div class="mb-3 col-md-12">
                            <label class="form-label">Description</label>
                            <textarea wire:model.defer="description" class="form-control" rows="3" placeholder="Enter description..."></textarea>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">New Password (Optional)</label>
                            <input type="password" wire:model.defer="password" class="form-control" placeholder="Leave blank to keep current password">
                        </div>
                    </div>


                    </div>
                    {{-- section two --}}
                    <div class="col-md-4">
                    <div class="row">

                                   <h6 class="card-title">Brand Fees</h6>
 
<div class="mb-3 col-md-6">
    <label>EFT Fees</label>
    <input type="number" wire:model.defer="eft_fees" class="form-control" placeholder="0.0%" min="0" step="any">
</div>

<div class="mb-3 col-md-6">
    <label>Interac Fees</label>
    <input type="number" wire:model="interac_fees" class="form-control" placeholder="0.0%" min="0" step="any">
</div>

<div class="mb-3 col-md-6">
    <label>Interac FTD Fees</label>
    <input type="number" wire:model="interacftd_fees" class="form-control" placeholder="0.0%" min="0" step="any">
</div>

<div class="mb-3 col-md-6">
    <label>Manual Wire Fees</label>
    <input type="number" wire:model="mwire_fees" class="form-control" placeholder="0.0%" min="0" step="any">
</div>

<div class="mb-3 col-md-6">
    <label>Manual EFT Fees</label>
    <input type="number" wire:model="meft_fees" class="form-control" placeholder="0.0%" min="0" step="any">
</div>

<div class="mb-3 col-md-6">
    <label>Credit Card Fees</label>
    <input type="number" wire:model="cc_fees" class="form-control" placeholder="0.0%" min="0" step="any">
</div>

                         <h6 class="card-title">Referrer Fees</h6>
<div class="mb-3 col-md-6">
    <label>Referrer EFT Fees</label>
    <input type="number" wire:model.defer="reft_fees" class="form-control" placeholder="0.0%" min="0" step="any">
    </div>
<div class="mb-3 col-md-6">
    <label>Referrer Interac Fees</label>
    <input type="number" wire:model="rinterac_fees" class="form-control" placeholder="0.0%" min="0" step="any">
    </div>
<div class="mb-3 col-md-6">
    <label>Referrer Interac FTD Fees</label>
    <input type="number" wire:model="rinteracftd_fees" class="form-control" placeholder="0.0%" min="0" step="any">
    </div>
<div class="mb-3 col-md-6">
    <label>Referrer Manual Wire Fees</label>
    <input type="number" wire:model="rmwire_fees" class="form-control" placeholder="0.0%" min="0" step="any">
    </div>
<div class="mb-3 col-md-6">
    <label>Referrer Manual EFT Fees</label>
    <input type="number" wire:model="rmeft_fees" class="form-control" placeholder="0.0%" min="0" step="any">
    </div>
<div class="mb-3 col-md-6">
    <label>Referrer Credit Card Fees</label>
    <input type="number" wire:model="rcc_fees" class="form-control" placeholder="0.0%" min="0" step="any">
    </div>
    
          

                       
                    </div>


                    </div>
                    </div>

                   <div class="d-flex justify-content-between mt-4">
    <div>
        <button type="submit" class="btn btn-primary me-2">Save Changes</button>

        <button type="button" class="btn btn-danger"
    onclick="if (confirm('Are you sure you want to delete this brand?')) { @this.confirmDelete() }">
    Delete Brand
</button>

    </div>
</div>

                </form>

            </div>
        </div>
    </div>
</div>
