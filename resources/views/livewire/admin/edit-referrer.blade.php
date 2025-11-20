<div class="col-xl-6 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Referrer</h4>
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

                <form wire:submit.prevent="saveReferrer">
                    <div class="row">

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Referrer Name</label>
                            <input type="text" wire:model.defer="referrer_name" class="form-control" placeholder="Enter Referrer Name">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" wire:model.defer="username" class="form-control" placeholder="Enter Username" readonly>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Status</label>
                            <select wire:model.defer="status" class="default-select form-control wide">
                                <option value="1">Active</option>
                                <option value="2">Suspended</option>
                            </select>
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

                   <div class="d-flex justify-content-between mt-4">
    <div>
        <button type="submit" class="btn btn-primary me-2">Save Changes</button>

        <button type="button" class="btn btn-danger"
    onclick="if (confirm('Are you sure you want to delete this referrer?')) { @this.confirmDelete() }">
    Delete Referrer
</button>

    </div>
</div>

                </form>

            </div>
        </div>
    </div>
</div>
