<x-app-layout>
        <x-slot name="title">User Management</x-slot>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Users Management</h4>
            <button class="btn btn-outline-primary btn-xs" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus me-1"></i> Add New User
            </button>
        </div>

        <div class="card-body">
            @livewire('users-management-table')
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @livewire('create-user-form')
                </div>
            </div>
        </div>
    </div>
    <script>
    window.addEventListener('user-added-success', event => {
        alert('User added successfully!');
    });
</script>

    <script>
        window.addEventListener('close-modal', () => {
    const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
    modal.hide();
});
    </script>
</x-app-layout>
