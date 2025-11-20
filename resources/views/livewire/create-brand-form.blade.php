<form wire:submit.prevent="saveBrand">
    <div wire:loading.delay class="text-center my-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="row">
        <div class="mb-3 col-md-6">
            <label>Brand Name</label>
            <input type="text" wire:model="brand_name" class="form-control" placeholder="Brand Name">
            @error('brand_name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3 col-md-6">
            <label>Username</label>
            <input type="text" wire:model="username" class="form-control" placeholder="Username">
            @error('username') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3 col-md-6">
            <label>Email</label>
            <input type="email" wire:model="email" class="form-control" placeholder="Email">
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3 col-md-6">
    <label>Status</label>
    <select wire:model="status" class="form-control">
        <option value="">-- Select Status --</option>
        <option value="1">Active</option>
        <option value="2">Suspended</option>
    </select>
    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
</div>
 <div class="mb-3 col-md-6">
    <label>EFT Fees</label>
    <input type="number" wire:model="eft_fees" class="form-control" placeholder="0.0%" min="0" step="any">
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
            <label>New Password (Optional)</label>
            <input type="password" wire:model="password" class="form-control" placeholder="Leave blank to keep current password">
            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3 col-md-12">
            <label>Description</label>
            <textarea wire:model="description" class="form-control" placeholder="Description"></textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="text-end">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Brand</button>
        </div>
    </div>
</form>
