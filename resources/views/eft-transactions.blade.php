<x-app-layout>
        <x-slot name="title">EFT Payments</x-slot>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">EFT Payments</h4>
        </div>

        <div class="card-body">
            @livewire('eft-payments-table')
        </div>
    </div>
</x-app-layout>
