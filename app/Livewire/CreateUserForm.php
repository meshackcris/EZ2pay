<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LegacyUser;
use App\Models\Brand;
use Illuminate\Support\Facades\Hash;

class CreateUserForm extends Component
{
    public $FirstName;
    public $LastName;
    public $Email;
    public $PhoneNumber;
    public $Password;
    public $Balance = 0; // Default balance
    public $Brand_Id;

    protected $rules = [
        'FirstName'   => 'required|string|max:255',
        'LastName'    => 'required|string|max:255',
        'Email'        => 'required|email|max:255|unique:Users,Email',
        'PhoneNumber' => 'required|string|max:15|unique:Users,PhoneNumber',
        'Password'     => 'required|string|min:6',
        'Balance'     => 'required|int',
        'Brand_Id'    => '',
    ];

    public function registerUser()
    {
        $validated = $this->validate();
        // Hash the password before saving
        $validated['Password'] = Hash::make($validated['Password']);
        // Duplicate 'Email' to 'email' (lowercase)
    $validated['email'] = $validated['Email'];
        LegacyUser::create($validated);

        $this->reset(['FirstName', 'LastName', 'Email', 'PhoneNumber', 'Password', 'Brand_Id']);

        $this->dispatch('userAdded');
        $this->dispatch('close-modal');
        $this->dispatch('user-added-success', type: 'browser');
    }

    public function render()
    {
        return view('livewire.create-user-form', [
        'brands' => Brand::all()
    ]);
    }
}
