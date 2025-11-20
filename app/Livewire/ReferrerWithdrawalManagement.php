<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Support\AppliesSearchTerms;
use App\Models\ReferrerWithdrawal;
use App\Models\Referrer;
use Illuminate\Support\Str;
use App\Models\AptPayTransaction;

class ReferrerWithdrawalManagement extends Component
{
    use WithPagination;
    use AppliesSearchTerms;
    protected $paginationTheme = 'bootstrap';

    // Filters
    public $search = '';
    public $startDate;
    public $endDate;

    // Modal state
    public $selectedWithdrawalId;
    public $transactionHash;
    public $rate;
    public $finalAmount;
    public $rejectionReason;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStartDate() { $this->resetPage(); }
    public function updatingEndDate() { $this->resetPage(); }

    public function clearFilters()
    {
        $this->search = '';
        $this->startDate = null;
        $this->endDate = null;
    }

    /** --------------------
     * Queries
     ---------------------*/
    public function getQuery($status)
    {
       $query = ReferrerWithdrawal::with('referrer')
        ->with('transactions.legacyUser.brand')
        ->where('Status', $status);

        $this->applySearchTerms($query, $this->search, function ($subQuery, $term) {
            $subQuery->whereHas('referrer', function ($q) use ($term) {
                $q->where('name', 'ilike', "%{$term}%")
                  ->orWhere('email', 'ilike', "%{$term}%");
            });
        });

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        return $query->orderByDesc('created_at');
    }

    /** --------------------
     * Actions ---------------------*/
    public function approveWithdrawal()
    {
        $withdrawal = ReferrerWithdrawal::findOrFail($this->selectedWithdrawalId);

        $withdrawal->update([
            'Status'          => 7, // Completed
            'TransactionHash' => $this->transactionHash,
            'Fees'            => $this->fees,
            'Rate'            => $this->rate,
            'FinalAmount'     => $this->finalAmount,
        ]);
        $transactions = AptPayTransaction::where('WithdrawalId', $withdrawal->Id)->get();
        foreach ($transactions as $transaction) {
            $transaction->update(['IsCommissionPaid' => true]);
        }

        $this->dispatch('switch-to-approved');
        session()->flash('success', 'Withdrawal approved successfully.');
    }

    public function rejectWithdrawal()
    {
        $withdrawal = ReferrerWithdrawal::findOrFail($this->selectedWithdrawalId);

    // Credit back the balance to the referrer
    $referrer = $withdrawal->referrer; // assuming relation exists
    if ($referrer) {
        $referrer->increment('balance', $withdrawal->Amount);
        $withdrawal->update([
            'Status'       => 9, // Rejected
            'ErrorMessage' => $this->rejectionReason,
        ]);
        $transactions = AptPayTransaction::where('WithdrawalId', $withdrawal->Id)->get();
        foreach ($transactions as $transaction) {
            $transaction->update(['WithdrawalId' => null]);
        }

        $this->dispatch('switch-to-rejected');
        session()->flash('success', 'Withdrawal rejected and amount credited back to referrer.');
        return;
    }
        session()->flash('error', 'An error occurred please contact the developer.');
    }

    public function deleteWithdrawal()
    {
        $withdrawal = ReferrerWithdrawal::findOrFail($this->selectedWithdrawalId);
        $withdrawal->delete();

        $this->dispatch('close-modal');
        session()->flash('success', 'Withdrawal deleted.');
    }

    public function editWithdrawal()
    {
        $withdrawal = ReferrerWithdrawal::findOrFail($this->selectedWithdrawalId);
        $amount = $withdrawal->Amount;
        $rate   = $this->rate;

        $withdrawal->update([
            'TransactionHash' => $this->transactionHash,
            'Rate'            => $rate,
            'FinalAmount' => $amount/ $rate,

        ]);

        $this->dispatch('close-modal');
        session()->flash('success', 'Withdrawal updated.');
    }

    public function loadFees($withdrawalId)
    {
        $withdrawal = ReferrerWithdrawal::findOrFail($withdrawalId);

        $this->selectedWithdrawalId = $withdrawal->Id;
        $this->transactionHash      = $withdrawal->TransactionHash;
        $this->rate                 = $withdrawal->Rate;
        $this->finalAmount          = $withdrawal->FinalAmount;
    }

    public function render()
    {
        $pendingWithdrawals  = $this->getQuery(3)->paginate(10, ['*'], 'pendingPage');   // Pending
        $approvedWithdrawals = $this->getQuery(7)->paginate(10, ['*'], 'approvedPage');  // Approved
        $rejectedWithdrawals = $this->getQuery(9)->paginate(10, ['*'], 'rejectedPage');  // Rejected

        $pendingTotal  = ReferrerWithdrawal::where('Status', 3)->sum('Amount');
        $approvedTotal = ReferrerWithdrawal::where('Status', 7)->sum('Amount');
        $rejectedTotal = ReferrerWithdrawal::where('Status', 9)->sum('Amount');
        $totalWithdrawal = ReferrerWithdrawal::sum('Amount');

        return view('livewire.referrer-withdrawal-management', compact(
            'pendingWithdrawals',
            'approvedWithdrawals',
            'rejectedWithdrawals',
            'pendingTotal',
            'approvedTotal',
            'rejectedTotal',
            'totalWithdrawal'
        ));
    }
}
