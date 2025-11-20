<form wire:submit.prevent="registerUser">
    <div wire:loading.delay class="text-center my-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="row">
        <div class="mb-3 col-md-6">
            <label>First Name</label>
            <input type="text" wire:model="FirstName" class="form-control" placeholder="First Name">
            @error('FirstName') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3 col-md-6">
            <label>Last Name</label>
            <input type="text" wire:model="LastName" class="form-control" placeholder="Last Name">
            @error('LastName') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3 col-md-6">
            <label>Email</label>
            <input type="email" wire:model="Email" class="form-control" placeholder="Email">
            @error('Email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3 col-md-6">
            <label>Brand</label>
            <select wire:model="Brand_Id" class="form-select">
                <option value="">Select Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                @endforeach
            </select>
            @error('Brand_Id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3 col-md-6">
            <label>Password</label>
            <input type="password" wire:model="Password" class="form-control" placeholder="Password">
            @error('Password') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3 col-md-6">
            <label>Phone Number</label>
            <input type="text" wire:model="PhoneNumber" class="form-control" placeholder="Phone Number">
            @error('PhoneNumber') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="text-end">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Register</button>
        </div>
    </div>
</form>
