<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Support\AppliesSearchTerms;
use App\Models\AptPayTransaction;
use App\Models\Brand;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InteracPaymentsTable extends Component
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
    public array $cryptoCurrency = [];
    public $transactionType = 2; // Interac default
    public array $selectedBrand = [];

    protected $queryString = ['perPage'];

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
        $this->selectedBrand = [];

        // Keep transactionType set to 2
    }

    public function exportToCSV(): StreamedResponse
    {
        $fileName = 'interact_export_' . now()->format('Y_m_d_His') . '.csv';

        // Export all filtered transactions ordered by CreatedAt (ascending)
        $transactions = $this->getFilteredQuery()->reorder()->orderByDesc('CreatedAt')->with('legacyUser')->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
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

    public function getFilteredQuery()
    {
        $query = AptPayTransaction::with([
            'legacyUser' => fn($q) => $q->select('Id', 'FirstName', 'LastName', 'Brand_Id')
            ->with('brand:id,brand_name')
        ])->where('TransactionType', 2); // Force Interac

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('CreatedAt', [$this->startDate, $this->endDate]);
        }

        if (!empty($this->status)) {
            $query->whereIn('Status', array_map('intval', $this->status));
        }
        // Filter by selected Brand
        if (!empty($this->selectedBrand)) {
            $query->whereHas('legacyUser', function ($q) {
                $q->whereIn('Brand_Id', array_map('intval', $this->selectedBrand));
            });
        }

        if ($this->minAmount !== null) {
            $query->where('Amount', '>=', $this->minAmount);
        }

        if ($this->maxAmount !== null) {
            $query->where('Amount', '<=', $this->maxAmount);
        }

        if (!empty($this->direction)) {
            $query->whereIn('PaymentDirection', array_map('intval', $this->direction));
        }
        
        if (!empty($this->cryptoCurrency)) {
            $query->whereIn('CryptoCurrency', array_filter($this->cryptoCurrency));
        }

        $this->applySearchTerms($query, $this->search, function ($q, $term) {
            $q->where('ReferenceNumber', 'ilike', "%{$term}%")
              ->orWhere('Currency', 'ilike', "%{$term}%")
              ->orWhereRaw('CAST("Amount" as TEXT) ilike ?', ["%{$term}%"]) // make sure amount is searchable
              ->orWhereHas('legacyUser', function ($subQ) use ($term) {
                  $subQ->where('FirstName', 'ilike', "%{$term}%")
                       ->orWhere('LastName', 'ilike', "%{$term}%")
                       ->orWhereHas('brand', function ($brandQ) use ($term) {
                           $brandQ->where('brand_name', 'ilike', "%{$term}%");
                       });
              });
        });



        return $query->orderByDesc('CreatedAt');
    }

    public function render()
    {
        $brands = Brand::select('id', 'brand_name')->orderBy('brand_name')->get();
        $transactions = $this->getFilteredQuery()->paginate($this->perPage);
        $this->dispatch('livewire-updated');
        return view('livewire.interac-payments-table', compact('transactions','brands'));
    }
}
