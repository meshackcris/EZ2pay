<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Brand;
use App\Models\Referrer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewBrandAddedNotification;
use App\Notifications\BrandWelcomeNotification;

class CreateBrandForm extends Component
{
    public $brand_name, $username, $status, $description, $password, $email, $eft_fees, $interac_fees, $interacftd_fees, $meft_fees, $mwire_fees, $cc_fees;
    public $referrer;
    public $referrerList = [];
    public function mount()
    {
        // Initialize referrerList with existing referrers
        $this->referrerList = Referrer::select('username', 'email')->orderBy('username')->get();
    }
    protected $listeners = ['setReferrer'];

public function setReferrer($ref)
{
    $this->referrer = $ref;
}

    protected $rules = [
        'brand_name'   => 'required|string|max:255',
        'username'     => 'required|string|max:255|unique:brands,username',
        'eft_fees'    => 'nullable|numeric|min:0|max:100',
        'interac_fees'    => 'nullable|numeric|min:0|max:100',
        'interacftd_fees'    => 'nullable|numeric|min:0|max:100',
        'meft_fees'    => 'nullable|numeric|min:0|max:100',
        'mwire_fees'    => 'nullable|numeric|min:0|max:100',
        'cc_fees'    => 'nullable|numeric|min:0|max:100',
        'email'        => 'required|email|max:255|unique:brands,email',
        'status'       => 'required|string|max:100',
        'referrer'     => 'nullable|string|max:255',
        'description'  => 'nullable|string|max:1000',
        'password'     => 'required|string|min:6',
    ];

   public function saveBrand()
{
    $validated = $this->validate();
    // Password is always required, hash it directly
    $validated['password'] = Hash::make($validated['password']);
    $brand = Brand::create($validated);

    $this->reset(['brand_name', 'username', 'email', 'status', 'referrer', 'description', 'password']);
    
    Notification::route('mail', 'support@orion-pay.ca')
    ->notify(new NewBrandAddedNotification($brand));
    $brand->notify(new BrandWelcomeNotification($brand));
    $this->dispatch('brandAdded');
    $this->dispatch('close-modal');
    $this->dispatch('brand-added-success', type: 'browser');
}


    public function render()
    {
        return view('livewire.create-brand-form');
    }
}
