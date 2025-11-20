<x-app-layout>
        <x-slot name="title">Payment Management</x-slot>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Payment Management</h4>
        </div>

        <div class="card-body">
            @livewire('transaction-table')
        </div>
    </div>
</x-app-layout>
