<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Referrer;

class EditReferrer extends Component
{
    public $referrer;
    public $referrer_name, $username, $status, $description, $password, $email;
    public $referrerId;

    public function mount($referrerId)
    {
        $this->referrerId = $referrerId;

        $this->referrer = Referrer::findOrFail($this->referrerId);

        $this->referrer_name = $this->referrer->referrer_name;
        $this->username = $this->referrer->username;
        $this->status = $this->referrer->status;
        $this->description = $this->referrer->description;
        $this->email = $this->referrer->email;
    }

    public function saveReferrer()
    {
        $this->validate([
            'referrer_name' => 'required|string|max:255',
            'status' => 'required|numeric',
            'description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
        ]);

        $this->referrer->update([
            'referrer_name' => $this->referrer_name,
            'status' => $this->status,
            'description' => $this->description,
            'email' => $this->email,
            'password' => $this->password ? bcrypt($this->password) : $this->referrer->password,
        ]);

        session()->flash('success', 'referrer updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.edit-referrer');
    }


public function confirmDelete()
{
    if ($this->referrer) {
        $this->referrer->delete();


 return redirect()->route('referrers')
            ->with('success', 'referrer deleted successfully.');    }
}

}
