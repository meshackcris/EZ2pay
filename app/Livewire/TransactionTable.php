<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Support\AppliesSearchTerms;
use App\Models\AptPayTransaction;
use App\Models\Brand;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Notifications\DepositApprovedNotification;
use Carbon\Carbon;


class TransactionTable extends Component
{
    use WithPagination;
    use AppliesSearchTerms;
    protected $paginationTheme = 'tailwind';

    public $perPage = 10;
    public $search = '';
    public $startDate;
    public $endDate;
    public array $status = [];
    public $minAmount;
    public $maxAmount;
    public array $direction = [];
    public $cryptoCurrency;
    public array $transactionType = [];
    public array $selectedBrands = [];


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
        $this->cryptoCurrency = null;
        $this->transactionType = [];
        $this->selectedBrands = [];
    }


public function exportToCSV(): StreamedResponse
{
    $fileName = 'transactions_export_' . now()->format('Y_m_d_His') . '.csv';

    // Export all filtered transactions ordered by CreatedAt (ascending)
    $transactions = $this->getFilteredQuery()->reorder()->orderByDesc('CreatedAt')->with('legacyUser')->get();

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


   public function render()
{
    $this->dispatch('livewire-updated'); // Initialize Select2 on page load
    //dd($this->status);
    $brands = Brand::select('id', 'brand_name')->orderBy('brand_name')->get();
    $transactions = $this->getFilteredQuery()->paginate($this->perPage);
    return view('livewire.transaction-table', compact('transactions','brands'));
}

public function getFilteredQuery()
{
     $query = AptPayTransaction::with([
    'legacyUser' => fn($q) => $q->select('Id', 'FirstName', 'LastName', 'Brand_Id')
                                ->with('brand:id,brand_name')
])->where('PaymentDirection', 1);
// Filter: Date Range
if ($this->startDate && $this->endDate) {
    $query->whereBetween('CreatedAt', [
        Carbon::parse($this->startDate)->startOfDay(),
        Carbon::parse($this->endDate)->endOfDay()    
    ]);
}

// Filter: Status (allow multi-select)
if (!empty($this->status)) {
    $query->whereIn('Status', array_map('intval', $this->status));
}
// Filter by selected Brands
if (!empty($this->selectedBrands)) {
    $query->whereHas('legacyUser', function ($q) {
        $q->whereIn('Brand_Id', array_map('intval', $this->selectedBrands));
    });
}


// Filter: Min & Max Amount
if ($this->minAmount !== null) {
    $query->where('Amount', '>=', $this->minAmount);
}
if ($this->maxAmount !== null) {
    $query->where('Amount', '<=', $this->maxAmount);
}

if (!empty($this->direction)) {
    $query->whereIn('PaymentDirection', array_map('intval', $this->direction));
}

// Filter: CryptoCurrency
if (!empty($this->cryptoCurrency)) {
    $query->where('CryptoCurrency', $this->cryptoCurrency);
}

// Filter: TransactionType
if (!empty($this->transactionType)) {
    $query->whereIn('TransactionType', array_map('intval', $this->transactionType));
}
   $this->applySearchTerms($query, $this->search, function ($q, $term) {
        $q->where('ReferenceNumber', 'ilike', "%{$term}%")
          ->orWhere('Currency', 'ilike', "%{$term}%")
          ->orWhereRaw('CAST("Amount" as TEXT) ilike ?', ["%{$term}%"]) // ensure numeric searchable
          ->orWhereHas('legacyUser', function ($subQ) use ($term) {
              $subQ->where('FirstName', 'ilike', "%{$term}%")
                   ->orWhere('LastName', 'ilike', "%{$term}%")
                   ->orWhere('Email', 'ilike', "%{$term}%")
                   ->orWhere('PhoneNumber', 'ilike', "%{$term}%")
                   ->orWhereHas('brand', function ($brandQ) use ($term) {
                       $brandQ->where('brand_name', 'ilike', "%{$term}%");
                   });
          });
    });



    return $query->orderByDesc('CreatedAt');
}

    public function deletePOP($id)
    {
        $trx = AptPayTransaction::findOrFail($id);
        $trx->Pop = NULL;
        $trx->Status= 3;
        $trx->save();

    $user = $trx->user; 
    session()->flash('success', 'POP deleted successfully.');
    }
        
    
public function updateTransactionStatus($transactionId, $newStatus)
{
    $transaction = AptPayTransaction::find($transactionId);

    if ($transaction) {
    // Get brand and referrer
$brand    = $transaction->legacyUser->brand;
$referrer = null;
if($brand){
$referrer = $brand->referrerRelation;
    }
        $transaction->Status = $newStatus;
        $transaction->save();
        if ($newStatus == 7) { // Assuming 7 is the status for 'Completed'
            $transaction->legacyUser->notify(new DepositApprovedNotification($transaction));
            if (!$transaction->Settled) {
                $transaction->legacyUser->Balance += intval($transaction->Amount);
                $transaction->legacyUser->save();
                // Settle the transaction if it was not already settled
                $transaction->Settled = true;
                $transaction->save();

                //Calculate Ref Comissions
                
            if ($referrer) {
                // Map TransactionType → brand fee column
                $feeColumns = [
                    1 => 'rmwire_fees',      // Wire
                    2 => 'rinterac_fees',    // Interac
                    4 => 'rcc_fees',         // Credit Card
                    5 => 'rmwire_fees',      // Manual Wire
                    6 => 'rmeft_fees',       // Manual EFT
                ];

                $txnType = $transaction->TransactionType;

                if (isset($feeColumns[$txnType])) {
                    $feeColumn = $feeColumns[$txnType];
                    $feePercent = $brand->$feeColumn; // e.g. 1.5 means 1.5%

                   

                    // Calculate commission
                    if($feePercent){
                    $commission = intval($transaction->Amount) * ($feePercent / 100);

                    // Increment referrer balance
                    $referrer->balance += $commission;
                    $referrer->save();
                    
                    $transaction->Rcomms = $commission; 
                    $transaction->save();
                }
                }
            }


            }
        }
        session()->flash('success', 'Transaction status updated successfully.');
    } else {
        session()->flash('error', 'Transaction not found.');
    }
}


}
