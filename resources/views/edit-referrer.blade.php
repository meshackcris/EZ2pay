 <x-app-layout>
        <x-slot name="title">Edit Referrer</x-slot>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Referrer</h4>
        </div>

        <div class="card-body">
        @livewire('admin.edit-referrer', ['referrerId' => $id])
        </div>
    </div>
</x-app-layout>
