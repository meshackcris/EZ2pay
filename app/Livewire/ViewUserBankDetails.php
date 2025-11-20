<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Brand;
use Illuminate\Support\Facades\Hash;

class ViewUserBankDetails extends Component
{
    public $brand_name, $username, $status, $registration_link, $referrer, $description, $password, $email;

    protected $rules = [
        'brand_name'   => 'required|string|max:255',
        'username'     => 'required|string|max:255|unique:brands,username',
        'email'        => 'required|email|max:255|unique:brands,email',
        'status'       => 'required|string|max:100',
        'registration_link' => 'required|url|max:255',
        'referrer'     => 'nullable|string|max:255',
        'description'  => 'nullable|string|max:1000',
        'password'     => 'required|string|min:6',
    ];

   public function saveBrand()
{
    $validated = $this->validate();
    // Password is always required, hash it directly
    $validated['password'] = Hash::make($validated['password']);

    Brand::create($validated);

    $this->reset(['brand_name', 'username', 'email', 'status', 'registration_link', 'referrer', 'description', 'password']);

    $this->dispatch('brandAdded');
    $this->dispatch('close-modal');
    $this->dispatch('brand-added-success', type: 'browser');
}


    public function render()
    {
        return view('livewire.view-user-bank-details');
    }
}
