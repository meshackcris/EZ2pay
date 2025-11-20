<x-app-layout>
        <x-slot name="title">Interact Payments</x-slot>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Interact Payments</h4>
        </div>

        <div class="card-body">
            @livewire('interac-payments-table')
        </div>
    </div>
</x-app-layout>
