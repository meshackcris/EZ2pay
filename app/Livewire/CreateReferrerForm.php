<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Referrer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewReferrerAddedNotification;
use App\Notifications\RefWelcomeNotification;

class CreateReferrerForm extends Component
{
    public $referrer_name, $username, $status, $referrer, $description, $password, $email;

    protected $rules = [
        'referrer_name'   => 'required|string|max:255',
        'username'     => 'required|string|max:255|unique:referrers,username',
        'email'        => 'required|email|max:255|unique:referrers,email',
        'status'       => 'required|string|max:100',
        'description'  => 'nullable|string|max:1000',
        'password'     => 'required|string|min:6',
    ];

   public function saveReferrer()
{
    $validated = $this->validate();
    // Password is always required, hash it directly
    $validated['password'] = Hash::make($validated['password']);
    $referrer = Referrer::create($validated);

    $referrer->notify(new RefWelcomeNotification($referrer));

    $this->reset(['referrer_name', 'username', 'email', 'status', 'description', 'password']);
    
    $this->dispatch('referrerAdded');
    $this->dispatch('close-modal');
    $this->dispatch('referrer-added-success', type: 'browser');
}


    public function render()
    {
        return view('livewire.create-referrer-form');
    }
}
