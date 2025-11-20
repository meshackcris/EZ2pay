<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LegacyUser;
use App\Models\AptPayTransaction;
use App\Models\Brand;
use Livewire\WithPagination;
use App\Support\AppliesSearchTerms;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\AptPayService;

class UsersManagementTable extends Component
{
    use WithPagination;
    use AppliesSearchTerms;
    protected $paginationTheme = 'tailwind';

    public $perPage = 10;
    public $search = '';
    public array $status = [];
    public $startDate;
    public $endDate;
    public $minBalance;
    public $maxBalance;
    public array $selectedBrand = [];
    public $selectedUserId = null;

    public $aptPayToken;
    public $activeUserID;
    public $account_title;
    public $account_type;
    public $transit_number;
    public $institution_number;
    public $account_number;
    public $currency;

    public $available_balance;
    public $current_balance;
    public $overdraft_limit;

    public $holder_name;
    public $email;
    public $phone_number;

    public $street_address;
    public $city;
    public $province;
    public $postal_code;
    public $country;
    protected $listeners = ['triggerSearch' => 'runSearch'];

    
    
    protected $queryString = ['perPage']; // keeps perPage in the URL
    public $verificationStatusLabels = [
        1 => ['label' => 'New', 'class' => 'badge-secondary'],
        2 => ['label' => 'Pending Verification', 'class' => 'badge-warning'],
        3 => ['label' => 'Review', 'class' => 'badge-info'],
        4 => ['label' => 'Escalated', 'class' => 'badge-dark'],
        5 => ['label' => 'Accepted', 'class' => 'badge-primary'],
        6 => ['label' => 'Rejected', 'class' => 'badge-danger'],
        7 => ['label' => 'Expired', 'class' => 'badge-light'],
        8 => ['label' => 'Approved', 'class' => 'badge-success'],
        9 => ['label' => 'None', 'class' => 'badge-muted'],
    ];
    
    public function updatingPerPage()
    {
        $this->resetPage();
        $this->dispatch('users-updated');
    }
    public function updatingPage()
    {
        $this->dispatch('users-updated');
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->dispatch('users-updated');

    }
 public function clearFilters()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->status = [];
        $this->minBalance = null;
        $this->maxBalance = null;
        $this->selectedBrand = [];
        $this->dispatch('users-updated');

    }

    

public function getAllUserTransactions($userIds)
{
    $transactions = AptPayTransaction::whereIn('UserId', $userIds)
        ->orderByDesc('CreatedAt')
        ->select('ReferenceNumber', 'UserId', 'CreatedAt', 'TransactionType', 'PaymentDirection', 'Amount', 'Status')
        ->get()
        ->groupBy('UserId')
        ->map(function ($group) {
            return $group->take(3)->values()->toArray();
        })
        ->toArray();

    return $transactions;
}




    

public function exportToCSV()
{
    // Build the base query with needed relations and aggregates
    $query = $this->getFilteredUsers()
        // Ensure brand is loaded (getFilteredUsers already loads it, but safe to include)
        ->with(['brand:id,brand_name'])
        // Pre-compute totals to avoid N+1 queries
        ->withSum([
            'transactions as deposits_sum' => function ($q) {
                $q->where('PaymentDirection', 1)->where('Status', 7);
            }
        ], 'Amount')
        ->withSum([
            'transactions as withdrawals_sum' => function ($q) {
                $q->where('PaymentDirection', 3)->where('Status', 7);
            }
        ], 'Amount');

    // Export all filtered users, ordered by registration date (CreatedAt)
    $users = $query->orderByDesc('CreatedAt')->get();

    $verificationStatusLabels = $this->verificationStatusLabels;
    return new StreamedResponse(function () use ($users, $verificationStatusLabels) {
        $handle = fopen('php://output', 'w');

        // Align headers with table columns (excluding Actions and documents)
        fputcsv($handle, [
            'Registration Date',
            'Status',
            'Firstname',
            'Lastname',
            'Brand',
            'Email',
            'Phone',
            'Balance',
            'Total Deposits',
            'Total Withdrawals',
        ]);

        foreach ($users as $user) {
            $statusLabel = $verificationStatusLabels[$user->VerificationStatus]['label'] ?? 'Unknown';

            // Prefer eager sums if present; fallback to accessors
            $totalDeposits = isset($user->deposits_sum) ? $user->deposits_sum : ($user->total_deposits ?? 0);
            $totalWithdrawals = isset($user->withdrawals_sum) ? $user->withdrawals_sum : ($user->total_withdrawals ?? 0);

            fputcsv($handle, [
                $user->CreatedAt ? \Carbon\Carbon::parse($user->CreatedAt)->format('Y-m-d H:i') : 'N/A',
                $statusLabel,
                $user->FirstName,
                $user->LastName,
                $user->brand->brand_name ?? '-',
                $user->Email,
                $user->PhoneNumber,
                $user->Balance,
                $totalDeposits,
                $totalWithdrawals,
            ]);
        }

        fclose($handle);
    }, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename=users_export.csv',
    ]);
}

public function updateStatus($userId, $newStatus)
{

    $user = LegacyUser::find($userId);
    if ($user) {
        $user->VerificationStatus = $newStatus;
        $user->save();
        session()->flash('success', 'User status updated successfully.');
    }

}

public function runSearch()
{
    // This could be empty if you already use getFilteredUsers() in render()
    // Or place any logic if needed
}


public function loadDetails($token, $userId)
{
    session()->forget(['success', 'error']);
    $this->activeUserID = $userId;
    $this->aptPayToken = $token;
    $this->fetchAccountDetails();
}

    public function fetchAccountDetails()
        {
            $this->resetBankDetailsModal();
    $response = null;

do {
    $response = AptPayService::callApi('/avs/get-account-details', [
        'token' => $this->aptPayToken,
    ]);

    // If response is 404, wait 3 seconds and try again
    if (isset($response['error_code']) && $response['error_code'] == 404) {
        sleep(3); // Wait 3 seconds
    }

} while (
    isset($response['error_code']) &&
    $response['error_code'] == 404 &&
    (
        !isset($response['errors']['token']) ||
        $response['errors']['token'] !== 'AVS session not completed'
    )
);

    if ($response->successful()) {
        $data = $response->json();
       
        $account = $data['account'] ?? [];

        // Account Information
        $this->account_title       = $account['Title'] ?? null;
        $this->account_type        = $account['Type'] ?? null;
        $this->transit_number      = $account['TransitNumber'] ?? null;
        $this->institution_number  = $account['InstitutionNumber'] ?? null;
        $this->account_number      = $account['AccountNumber'] ?? null;
        $this->currency            = $account['Currency'] ?? null;

        // Balance Information
        $this->available_balance   = $account['Balance']['Available'] ?? null;
        $this->current_balance     = $account['Balance']['Current'] ?? null;
        $this->overdraft_limit     = $account['OverdraftLimit'] ?? null;

        // Holder Information
        $this->holder_name         = $account['Holder']['Name'] ?? null;
        $this->email               = $account['Holder']['Email'] ?? null;
        $this->phone_number        = $account['Holder']['PhoneNumber'] ?? null;

        // Address Information
        $address = $account['Holder']['Address'] ?? [];

        $this->street_address      = $address['CivicAddress'] ?? null;  // CivicAddress mapped to Street Address
        $this->city                = $address['City'] ?? null;
        $this->province            = $address['Province'] ?? null;
        $this->postal_code         = $address['PostalCode'] ?? null;
        $this->country             = $address['Country'] ?? null;

        

    } else {
        $tokenError = $response['errors']['token'] ?? null;

        if ($tokenError) {
            if (is_array($tokenError)) {
                // Multiple error messages
                $message = implode(' | ', $tokenError);
            } else {
                // Single error message
                $message = $tokenError;
            }
            
            session()->flash('error', 'Failed to load bank details. Error: ' . $message);

        }else {
            session()->flash('error', 'Failed to load bank details. Please try again later.');
        }

    }
}
public function toggleManualBankData()
{
    $user = LegacyUser::find($this->activeUserID);
    if ($user) {
        $user->ManualBankData = !$user->ManualBankData;
        // If toggling to manual, set the bank details
            $user->BankBalance = $this->current_balance;
            $user->BankInstitutionNumber = $this->institution_number;
            $user->BankTransitNumber = $this->transit_number;
            $user->BankAccountNumber = $this->account_number;

        $user->save();
        session()->flash('success', 'Manual bank data toggled successfully.');
    } else {
        session()->flash('error', 'User not found.');
    }
}

public function resetBankDetails()
{
    $user = LegacyUser::find($this->activeUserID);
    if ($user) {
        $user->AptPayToken = null;
        $user->AptPayUrl = null;
        $user->IsBankVerified = false;
        $user->save();

        session()->flash('success', 'User Bank info has been reset.');
        $this->resetBankDetailsModal();
    } else {
        session()->flash('error', 'User not found.');
    }
}

public function resetBankDetailsModal()
{
    // Account Information
        $this->account_title       = null;
        $this->account_type        = null;
        $this->transit_number      = null;
        $this->institution_number  = null;
        $this->account_number      = null;
        $this->currency            = null;

        // Balance Information
        $this->available_balance   = null;
        $this->current_balance     = null;
        $this->overdraft_limit     = null;

        // Holder Information
        $this->holder_name         = null;
        $this->email               = null;
        $this->phone_number        = null;

        // Address Information
        $address = [];

        $this->street_address      = null;  // CivicAddress mapped to Street Address
        $this->city                = null;
        $this->province            = null;
        $this->postal_code         = null;
        $this->country             = null;
    }
public function render()
{
    $users = $this->getFilteredUsers()->latest('CreatedAt')->paginate($this->perPage);

    $userIds = $users->getCollection()->pluck('Id')->toArray();
    $transactions = $this->getAllUserTransactions($userIds);

    $brands = cache()->remember('brands_list', 60, function () {
        return Brand::select('id', 'brand_name')->orderBy('brand_name')->get();
    });

    $this->dispatch('livewire-updated');

    return view('livewire.users-management-table', [
        'users' => $users,
        'brands' => $brands,
        'transactions' => $transactions,
    ]);
}



    public function getFilteredUsers($withBrand = true)
{
    $query = LegacyUser::select(
        'Id',
        'CreatedAt',
        'VerificationStatus',
        'KycSessionId',
        'FirstName',
        'LastName',
        'Email',
        'PhoneNumber',
        'Balance',
        'Brand_Id',
        'AptPayToken',
        'pad_agreement_path'
    );

    if ($withBrand) {
        $query->with('brand:id,brand_name');
    }

    $this->applySearchTerms($query, $this->search, function ($subQuery, $term) {
        $subQuery->where('FirstName', 'ilike', "%{$term}%")
                 ->orWhere('LastName', 'ilike', "%{$term}%")
                 ->orWhere('Email', 'ilike', "%{$term}%")
                 ->orWhere('PhoneNumber', 'ilike', "%{$term}%")
                 ->orWhereHas('brand', function ($q) use ($term) {
                     $q->where('brand_name', 'ilike', "%{$term}%");
                 });
    });

    if (!empty($this->status)) {
        $query->whereIn('VerificationStatus', array_map('intval', $this->status));
    }

    if (!empty($this->startDate) && !empty($this->endDate)) {
        $query->whereBetween('CreatedAt', [
            Carbon::parse($this->startDate)->startOfDay(),
            Carbon::parse($this->endDate)->endOfDay(),
        ]);
    }

    if (!empty($this->selectedBrand)) {
        $query->whereIn('Brand_Id', array_map('intval', $this->selectedBrand));
    }

    if (!empty($this->minBalance)) {
        $query->where('Balance', '>=', $this->minBalance);
    }

    if (!empty($this->maxBalance)) {
        $query->where('Balance', '<=', $this->maxBalance);
    }

    return $query;
}



}
