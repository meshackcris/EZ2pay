<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Brand;
use App\Models\Referrer;

class EditBrand extends Component
{
    public $brand;
    public $brand_name, $username, $status, $registration_link, $description, $password, $email, $eft_fees, $interac_fees, $interacftd_fees, $meft_fees, $mwire_fees, $cc_fees;
    public $reft_fees, $rinterac_fees, $rinteracftd_fees, $rmeft_fees, $rmwire_fees, $rcc_fees;
    public $brandId;

    public $referrer = '';
public $referrerList = [];

    protected $listeners = ['setReferrer'];

public function setReferrer($ref)
{
    $this->referrer = $ref;
}
    public function mount($brandId)
    {
        $this->brandId = $brandId;

        $this->brand = Brand::findOrFail($this->brandId);
        $this->referrerList = Referrer::select('username', 'email')->orderBy('username')->get();

        $this->brand_name = $this->brand->brand_name;
        $this->username = $this->brand->username;
        $this->eft_fees = $this->brand->eft_fees;
        $this->interac_fees = $this->brand->interac_fees;
        $this->interacftd_fees = $this->brand->interacftd_fees;
        $this->meft_fees = $this->brand->meft_fees;
        $this->mwire_fees = $this->brand->mwire_fees;
        $this->cc_fees = $this->brand->cc_fees;
        $this->reft_fees = $this->brand->reft_fees;
        $this->rinterac_fees = $this->brand->rinterac_fees;
        $this->rinteracftd_fees = $this->brand->rinteracftd_fees;
        $this->rmeft_fees = $this->brand->rmeft_fees;
        $this->rmwire_fees = $this->brand->rmwire_fees;
        $this->rcc_fees = $this->brand->rcc_fees;
        $this->status = $this->brand->status;
        $this->registration_link ="https://user.orion-pay.ca/?ref=" . $this->brand->ref_token;
        $this->referrer = $this->brand->referrer;
        $this->description = $this->brand->description;
        $this->email = $this->brand->email;
    }

    public function saveBrand()
    {
        $this->validate([
            'brand_name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'status' => 'required|numeric',
            'referrer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
        ]);

        $this->brand->update([
            'brand_name' => $this->brand_name,
            'username' => $this->username,
            'eft_fees' => $this->eft_fees,
            'interac_fees' => $this->interac_fees,
            'interacftd_fees' => $this->interacftd_fees,
            'meft_fees' => $this->meft_fees,
            'mwire_fees' => $this->mwire_fees,
            'cc_fees' => $this->cc_fees,
            'reft_fees' => $this->reft_fees,
            'rinterac_fees' => $this->rinterac_fees,
            'rinteracftd_fees' => $this->rinteracftd_fees,
            'rmeft_fees' => $this->rmeft_fees,
            'rmwire_fees' => $this->rmwire_fees,
            'rcc_fees' => $this->rcc_fees,
            'status' => $this->status,
            'referrer' => $this->referrer,
            'description' => $this->description,
            'email' => $this->email,
            'password' => $this->password ? bcrypt($this->password) : $this->brand->password,
        ]);

        session()->flash('success', 'Brand updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.edit-brand');
    }


public function confirmDelete()
{
    if ($this->brand) {
        $this->brand->delete();


 return redirect()->route('brands')
            ->with('success', 'Brand deleted successfully.');    }
}

}
