<x-app-layout>
    <x-slot name="title">Brands Management</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Brands Management</h4>
            <button class="btn btn-outline-primary btn-xs" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                <i class="fas fa-plus me-1"></i> Add New Brand
            </button>
        </div>

        <div class="card-body">
            @livewire('brand-management')
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addBrandModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add New Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @livewire('create-brand-form')
                </div>
            </div>
        </div>
    </div>
    <script>
    window.addEventListener('brand-added-success', event => {
        alert('Brand added successfully!');
    });
</script>

    <script>
        window.addEventListener('close-modal', () => {
    const modal = bootstrap.Modal.getInstance(document.getElementById('addBrandModal'));
    modal.hide();
});
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('addBrandModal');

    if (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            const $select = $('.ref-select');

            // Prevent re-initializing
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.select2({
                theme: 'classic',
                dropdownParent: $('#addBrandModal')
            });
             $('#referrer-select').on('change', function () {
            Livewire.dispatch('setReferrer', { ref: $(this).val()} );
        });
            
        });
    }
});
</script>

    </script>
</x-app-layout>
