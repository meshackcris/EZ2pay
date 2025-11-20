<x-app-layout>
        <x-slot name="title">Edit Brand</x-slot>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Brand</h4>
        </div>

        <div class="card-body">
        @livewire('admin.edit-brand', ['brandId' => $id])
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function () {

            const $select = $('.ref-select');

            // Prevent re-initializing
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.select2({
                theme: 'classic'            });
             $('#referrer-select').on('change', function () {
            Livewire.dispatch('setReferrer', { ref: $(this).val()} );
        });
            
});
</script>
</x-app-layout>
