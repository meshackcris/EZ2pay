<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\LegacyUser;
use App\Models\Brand;
use App\Models\AptPayTransaction;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Livewire\WithPagination;
use App\Support\AppliesSearchTerms;
use Illuminate\Support\Str;
use App\Models\KycSubmission;


class EditUser extends Component
{    
    use WithPagination;
    use AppliesSearchTerms;

    public $user;
    public $UserId;
    public $Email, $FirstName, $LastName, $PhoneNumber, $VerificationStatus, $IpAddress;
    public $Password; // Add this at the top of your class if not already declared
    protected $paginationTheme = 'tailwind';

    public $perPage = 10;
    public $search = '';
    public $startDate;
    public $endDate;
    public array $status = [];
    public $minAmount;
    public $maxAmount;
    public array $direction = [];
    public array $cryptoCurrency = [];
    public array $transactionType = [];
    public $Brand_Id;
    public $brands = [];
    public $padAgreement;



    public function mount($UserId)
    {
        $this->user = LegacyUser::findOrFail($UserId);
        $this->padAgreement = $this->user->has_pad_agreement ? 1 : 0; // Initialize padAgreement
        $this->UserId = $this->user->Id;
        $this->Email = $this->user->Email;
        $this->FirstName = $this->user->FirstName;
        $this->LastName = $this->user->LastName;
        $this->PhoneNumber = $this->user->PhoneNumber;
        $this->VerificationStatus = $this->user->VerificationStatus;
        $this->IpAddress = $this->user->IpAddress ?? '';
        $this->Brand_Id = $this->user->Brand_Id;
        $this->brands = Brand::select('id', 'brand_name')->get();

    }

    protected $queryString = ['perPage']; // keeps perPage in the URL

    public function updatingPerPage()
    {
        $this->resetPage();
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function clearFilters()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->status = [];
        $this->minAmount = null;
        $this->maxAmount = null;
        $this->direction = [];
        $this->cryptoCurrency = [];
        $this->transactionType = [];
    }

    public function exportToCSV(): StreamedResponse
    {
    $fileName = 'transactions_export_' . now()->format('Y_m_d_His') . '.csv';

    // Export all filtered transactions ordered by CreatedAt (ascending)
    $transactions = $this->getFilteredQuery()->reorder()->orderByDesc('CreatedAt')->with('legacyUser')->get(); // include user data

    $headers = [
        'Content-type'        => 'text/csv',
        'Content-Disposition' => "attachment; filename=$fileName",
        'Pragma'              => 'no-cache',
        'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
        'Expires'             => '0',
    ];

    $columns = ['Reference Number', 'Full Name', 'Amount', 'Status', 'Transaction Type','Direction', 'CryptoCurrency', 'Date'];

    $statusMap = [
        3 => 'Pending',
        4 => 'Processing',
        7 => 'Completed',
        9 => 'Failed',
        17 => 'Error',
    ];

    $typeMap = [
        1 => 'Wire',
        2 => 'Interact',
    ];

    $callback = function () use ($transactions, $columns, $statusMap, $typeMap) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($transactions as $txn) {
            $fullName = $txn->legacyUser?->FirstName . ' ' . $txn->legacyUser?->LastName;

            fputcsv($file, [
                $txn->ReferenceNumber,
                trim($fullName) ?: 'Unknown User',
                $txn->Amount,
                $statusMap[$txn->Status] ?? 'Unknown',
                $typeMap[$txn->TransactionType] ?? 'Unknown',
                $txn->Discriminator ?? 'N/A',
                $txn->CryptoCurrency ?? 'N/A',
                $txn->CreatedAt,
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    public function saveUser()
    {
        $this->validate([
            'Email' => 'required|email|max:255|unique:"Users",Email,' . $this->UserId . ',Id',
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'PhoneNumber' => 'required|string|max:20',
            'VerificationStatus' => 'required|numeric',
            'Password' => 'nullable|string|min:6', // password is optional, min 6 if present
        ]);
        $updateData = [
            'Email' => $this->Email,
            'email' => $this->Email,
            'FirstName' => $this->FirstName,
            'LastName' => $this->LastName,
            'PhoneNumber' => $this->PhoneNumber,
            'Brand_Id' => $this->Brand_Id,
            'VerificationStatus' => $this->VerificationStatus,
        ];
        // Handle PAD Agreement changes
        if ($this->padAgreement == 2) {
            $updateData['has_pad_agreement'] = false;
            $updateData['pad_agreement_path'] = null;
        } elseif ($this->padAgreement == 3) {
            $updateData['has_pad_agreement'] = true;
            $updateData['pad_agreement_path'] = Str::random(40); // use any string generator you like
        }
        if (!empty($this->Password)) {
            $updateData['password'] = bcrypt($this->Password);
        }

        if ($this->user->update($updateData)) {
            session()->flash('success', 'User updated successfully.');
        } else {
            session()->flash('error', 'Failed to update user.');
        }
}

    public function resetKyc()
    {
            KycSubmission::where('user_id', $this->UserId)->delete();
            session()->flash('success', 'KYC status reset successfully.');
        
    }

public function resetPOI()
{
    KycSubmission::where('user_id', $this->UserId)
        ->update([
            'document_front_path' => 'deleted',
            'document_back_path'  => 'deleted',
        ]);

    session()->flash('success', 'POI deleted successfully.');
}

public function resetPOR()
{
    KycSubmission::where('user_id', $this->UserId)
        ->update([
            'poa_path' => 'deleted',
        ]);

    session()->flash('success', 'POR deleted successfully.');
}

public function resetKycVid()
{
    KycSubmission::where('user_id', $this->UserId)
        ->update([
            'video_path' => 'deleted',
        ]);

    session()->flash('success', 'KYC video deleted successfully.');
}

public function resetWallet(){
    $this->user->WalletAddress = null;
    $this->user->save();
    session()->flash('success', 'Wallet address reset successfully.');
}
    public function confirmDelete()
    {
        if ($this->user) {
            $this->user->delete();

            return redirect()->route('user.management')->with('success', 'User deleted successfully.');
        }
    }
    public function getFilteredQuery()
{
    $query = $this->user->transactions()
    ->with([
        'legacyUser' => fn($q) => $q->select('Id', 'FirstName', 'LastName')
    ]);


    // Filter: Date Range
    if ($this->startDate && $this->endDate) {
        $query->whereBetween('CreatedAt', [$this->startDate, $this->endDate]);
    }

    // Filter: Status
    if (!empty($this->status)) {
        $query->whereIn('Status', array_map('intval', $this->status));
    }

    // Filter: Brand
    if (!empty($this->selectedBrand)) {
        $query->whereHas('legacyUser', function ($q) {
            $q->where('Brand_Id', $this->selectedBrand);
        });
    }

    // Min & Max Amount
    if ($this->minAmount !== null) {
        $query->where('Amount', '>=', $this->minAmount);
    }

    if ($this->maxAmount !== null) {
        $query->where('Amount', '<=', $this->maxAmount);
    }

    // Direction
    if (!empty($this->direction)) {
        $query->whereIn('PaymentDirection', array_map('intval', $this->direction));
    }

    // CryptoCurrency
    if (!empty($this->cryptoCurrency)) {
        $query->whereIn('CryptoCurrency', array_filter($this->cryptoCurrency));
    }

    // Transaction Type
    if (!empty($this->transactionType)) {
        $query->whereIn('TransactionType', array_map('intval', $this->transactionType));
    }

    // Search
    // $this->applySearchTerms($query, $this->search, function ($q, $term) {
    //     $q->where('ReferenceNumber', 'ilike', "%{$term}%")
    //       ->orWhere('Currency', 'ilike', "%{$term}%")
    //       ->orWhere('Amount', 'ilike', "%{$term}%")
    //       ->orWhereHas('legacyUser', function ($subQuery) use ($term) {
    //           $subQuery->where('FirstName', 'ilike', "%{$term}%")
    //             ->orWhere('LastName', 'ilike', "%{$term}%");
    //       });
    // });

    return $query->orderByDesc('CreatedAt');
}

    public function render()
    {
        $transactions = $this->getFilteredQuery()->paginate($this->perPage);
        $this->dispatch('livewire-updated');

        return view('livewire.admin.edit-user', [
            'transactions' => $transactions
        ]);
    }
    
}
