<x-app-layout>
        <x-slot name="title">Edit User</x-slot>
    <div class="">
        <div class="card-body">
        @livewire('admin.edit-user', ['UserId' => $id])
        </div>
    </div>
</x-app-layout>
