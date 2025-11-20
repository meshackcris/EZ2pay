 <x-app-layout>
    <x-slot name="title">Referrers Management</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Referrers Management</h4>
            <button class="btn btn-outline-primary btn-xs" data-bs-toggle="modal" data-bs-target="#addReferrerModal">
                <i class="fas fa-plus me-1"></i> Add New Referrer
            </button>
        </div>

        <div class="card-body">
            @livewire('referrer-management')
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addReferrerModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add New Referrer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @livewire('create-referrer-form')
                </div>
            </div>
        </div>
    </div>
    <script>
    window.addEventListener('referrer-added-success', event => {
        alert('Referrer added successfully!');
    });
</script>

    <script>
        window.addEventListener('close-modal', () => {
    const modal = bootstrap.Modal.getInstance(document.getElementById('addReferrerModal'));
    modal.hide();
});
    </script>
</x-app-layout>
