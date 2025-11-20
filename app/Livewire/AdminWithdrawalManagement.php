<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Support\AppliesSearchTerms;
use App\Models\AptPayTransaction;
use Carbon\Carbon;
use App\Notifications\WithdrawalSuccessfulNotification;
use App\Notifications\WithdrawalFailedNotification;
use Illuminate\Support\Facades\Session;
use App\Notifications\WithdrawalReceivedConfirmation;
use App\Services\AppBSignClient;

class AdminWithdrawalManagement extends Component
{
    use WithPagination;
    use AppliesSearchTerms;

    public $search = '';
    public $startDate;
    public $endDate;
    public array $status = [];

    public $selectedWithdrawalId;
    public $transactionHash;
    public $rate;
    public $fees;
    public $rejectionReason;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->status = [];
    }
    public function loadFees($withdrawalId)
    {
        $withdrawal = AptPayTransaction::findOrFail($withdrawalId);
        $this->selectedWithdrawalId = $withdrawal->Id;
        $this->transactionHash = $withdrawal->TransactionHash;
        $this->rate = $withdrawal->Rate;
        $this->fees = $withdrawal->Fees ?? 0; // Assuming 'Fees' is the column name in your database
    }
    public function deleteWithdrawal()
    {
        $withdrawal = AptPayTransaction::findOrFail($this->selectedWithdrawalId);
        $withdrawal->delete();
        $this->reset(['selectedWithdrawalId']);
        $this->dispatch('close-modal');
        session()->flash('success', 'Withdrawal deleted successfully.');
    }

    public function approveWithdrawal(AppBSignClient $signClient)
    {
        $withdrawal = AptPayTransaction::findOrFail($this->selectedWithdrawalId);

        $withdrawal->update([
            'TransactionHash' => $this->transactionHash,
            'Status' => 7,
        ]);
        $user = $withdrawal->legacyUser; // Assuming you have a user() relationship on AptPayTransaction
        if ($user) {
            $user->notify(new WithdrawalSuccessfulNotification());
            // Params App B expects on the route (e.g., ?user=123)
            $params = ['withdrawal' => $this->selectedWithdrawalId];

    // Ask App B to sign its own route:
            $signedUrl = $signClient->getSignedUrl('withdrawal.received', $params, 120);
            $user->notify(new WithdrawalReceivedConfirmation($signedUrl, $withdrawal));
        }

        session()->flash('success', 'Withdrawal approved successfully.');
        $this->reset(['selectedWithdrawalId', 'transactionHash']);
        $this->dispatch('switch-to-approved');
        $this->dispatch('close-approve-modal');
    }

    public function rejectWithdrawal()
    {
        $withdrawal = AptPayTransaction::findOrFail($this->selectedWithdrawalId);
        $withdrawal->update([
            'Status' => 9,
            'ErrorMessage' => $this->rejectionReason,
        ]);
        
        $deposit = AptPayTransaction::where('ReferenceNumber', $withdrawal->DepositId)->first();
        $deposit->DepositId = null;
        $deposit->save();

        $withdrawal->DepositId = null;
        $withdrawal->save();

        $user = $withdrawal->legacyUser; // Assuming you have a user() relationship on AptPayTransaction
        $user->Balance += $withdrawal->Amount;
        $user->save();
        if ($user) {
            $user->notify(new WithdrawalFailedNotification());
        }

        session()->flash('success', 'Withdrawal rejected successfully.');
        $this->reset(['selectedWithdrawalId', 'rejectionReason']);
        $this->dispatch('switch-to-rejected');
        $this->dispatch('close-reject-modal');
    } 

    public function editWithdrawal()
    {
        $withdrawal = AptPayTransaction::findOrFail($this->selectedWithdrawalId);

        $feePercent = $this->fees; // e.g. 2.5
        $amount = $withdrawal->Amount;
        $rate   = $this->rate;

        $withdrawal->update([
            'TransactionHash' => $this->transactionHash,
            'Rate' => $this->rate,
            'Fees' => $this->fees,
            'FinalAmount' => ($amount - ($amount * ($feePercent / 100))) / $rate,
        ]);
        

        session()->flash('success', 'Withdrawal rejected successfully.');
        $this->reset(['selectedWithdrawalId', 'transactionHash', 'rate', 'fees']);
        $this->dispatch('close-modal');
    }

    public function render()
{
    // Base filters for all withdrawals
    $baseQuery = AptPayTransaction::where('PaymentDirection', 3)
        ->when($this->startDate, fn($q) => $q->whereDate('CreatedAt', '>=', $this->startDate))
        ->when($this->endDate, fn($q) => $q->whereDate('CreatedAt', '<=', $this->endDate))
        ->when(!empty($this->status), function ($q) {
            $q->whereIn('Status', array_map('intval', $this->status));
        });

    $this->applySearchTerms($baseQuery, $this->search, function ($query, $term) {
        $query->whereHas('legacyUser', function ($q) use ($term) {
            $q->whereRaw('"FirstName" ILIKE ?', ["%{$term}%"])
              ->orWhereRaw('"LastName" ILIKE ?', ["%{$term}%"])
              ->orWhere('Email', 'like', "%{$term}%");
        });
    });

    // Pending list + total
    $pendingQuery = (clone $baseQuery)
        ->whereIn('Status', [3, 4])
        ->with('deposit')
        ->orderBy('CreatedAt', 'desc')
        ->paginate(10, pageName: 'pendingPage');

    $pendingTotal = (clone $baseQuery)->whereIn('Status', [3, 4])->sum('Amount');

    // Approved list + total
    $approvedQuery = (clone $baseQuery)
        ->whereIn('Status', [7, 10])
        ->with('deposit')
        ->orderBy('CreatedAt', 'desc')
        ->paginate(10, pageName: 'approvedPage');

    $approvedTotal = (clone $baseQuery)->whereIn('Status', [7, 10])->sum('Amount');

    // Rejected list + total
    $rejectedQuery = (clone $baseQuery)
        ->where('Status', 9)
        ->with('deposit')
        ->orderBy('CreatedAt', 'desc')
        ->paginate(10, pageName: 'rejectedPage');

    $rejectedTotal = (clone $baseQuery)->where('Status', 9)->sum('Amount');
    $totalWithdrawal = (clone $baseQuery)->sum('Amount');

    $this->dispatch('livewire-updated');

    return view('livewire.admin-withdrawal-management', [
        'pendingWithdrawals'  => $pendingQuery,
        'approvedWithdrawals' => $approvedQuery,
        'rejectedWithdrawals' => $rejectedQuery,
        'pendingTotal'        => $pendingTotal,
        'approvedTotal'       => $approvedTotal,
        'rejectedTotal'       => $rejectedTotal,
        'totalWithdrawal'     => $totalWithdrawal,
    ]);
}

}
