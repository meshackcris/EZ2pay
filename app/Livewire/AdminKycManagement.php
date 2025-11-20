<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Support\AppliesSearchTerms;
use App\Models\KycSubmission;
use Illuminate\Support\Facades\Session;
use App\Notifications\KycApprovedNotification;
use App\Notifications\KycRejectedNotification;

class AdminKycManagement extends Component
{
    use WithPagination;
    use AppliesSearchTerms;

    public $search = '';
    public $startDate;
    public $endDate;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->startDate = null;
        $this->endDate = null;
    }

    public function approve($id)
    {
        $kyc = KycSubmission::findOrFail($id);
        $kyc->status = 1; // Approved
        $kyc->save();

    $user = $kyc->user; // Assuming you have a user() relationship on KycSubmission
        
    if ($user) {
        $user->notify(new KycApprovedNotification());

    }
        Session::flash('success', 'KYC Approved Successfully.');
    }

    public function reject($kycId)
{
    $kyc = KycSubmission::findOrFail($kycId);
    $kyc->status = 2; // Rejected
    $kyc->save();
    $user = $kyc->user; // Assuming you have a user() relationship on KycSubmission

    if ($user) {
        $user->VerificationStatus = 6; // Update user's kyc_status to Rejected
        $user->save();
$user->notify(new KycRejectedNotification());
    }
    session()->flash('success', 'KYC submission has been rejected.');
    $this->resetPage();
}


    public function render()
    {
        $baseQuery = KycSubmission::with('user')
            ->when($this->startDate, fn ($query) => $query->whereDate('submitted_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($query) => $query->whereDate('submitted_at', '<=', $this->endDate));

        $this->applySearchTerms($baseQuery, $this->search, function ($query, $term) {
            $query->whereHas('user', function ($q) use ($term) {
                $q->where('FirstName', 'ilike', "%{$term}%")
                  ->orWhere('LastName', 'ilike', "%{$term}%")
                  ->orWhere('Email', 'ilike', "%{$term}%");
            });
        });

        return view('livewire.admin-kyc-management', [
            'pendingKycs' => (clone $baseQuery)->where('status', 0)->paginate(10, pageName: 'pendingPage'),
            'approvedKycs' => (clone $baseQuery)->where('status', 1)->paginate(10, pageName: 'approvedPage'),
            'rejectedKycs' => (clone $baseQuery)->where('status', 2)->paginate(10, pageName: 'rejectedPage'),
        ]);
    }
}
