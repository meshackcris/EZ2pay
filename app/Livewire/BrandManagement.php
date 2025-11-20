<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Brand;
use Livewire\WithPagination;
use App\Support\AppliesSearchTerms;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class BrandManagement extends Component
{

    use WithPagination;
    use AppliesSearchTerms;
    protected $paginationTheme = 'tailwind';

    public $perPage = 10;
    public $search = '';
    public array $status = [];
    public $startDate;
    public $endDate;
    protected $listeners = ['brandAdded' => '$refresh'];

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
    }



    public function getFilteredBrands()
{
    $query = Brand::with(['legacyUsers.transactions'])
        ->select('brand_name', 'username', 'id', 'created_at', 'status', 'ref_token', 'referrer');

    // Apply filters
    if (!empty($this->status)) {
        $query->whereIn('status', array_map('intval', $this->status));
    }

    if (!empty($this->startDate) && !empty($this->endDate)) {
        $query->whereBetween('created_at', [
            Carbon::parse($this->startDate)->startOfDay(),
            Carbon::parse($this->endDate)->endOfDay(),
        ]);
    }

    $this->applySearchTerms($query, $this->search, function ($q, $term) {
        $q->where('brand_name', 'ilike', "%{$term}%")
          ->orWhere('username', 'ilike', "%{$term}%")
          ->orWhere('id', 'ilike', "%{$term}%")
          ->orWhere('referrer', 'ilike', "%{$term}%");
    });

    // Latest before paginate
    $brands = $query->latest('created_at')->paginate($this->perPage);

    // Safe transformation after pagination
    $brands->getCollection()->transform(function ($brand) {
        $transactions = $brand->legacyUsers->flatMap->transactions;
        $brand->deposits_made = $transactions->where('PaymentDirection', 1)->sum('Amount');
        $brand->withdrawals_made = $transactions->where('PaymentDirection', 0)->sum('Amount');
        return $brand;
    });

    return $brands;
}


    public function exportToCSV(): StreamedResponse
    {
        $fileName = 'brands_export_' . now()->format('Y_m_d_His') . '.csv';

        // Export all filtered brands, ordered by registration date
        $query = Brand::with(['legacyUsers.transactions'])
            ->select('brand_name', 'username', 'id', 'created_at', 'status', 'ref_token', 'referrer');

        if (!empty($this->status)) {
            $query->whereIn('status', array_map('intval', $this->status));
        }

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay(),
            ]);
        }

        $this->applySearchTerms($query, $this->search, function ($q, $term) {
            $q->where('brand_name', 'ilike', "%{$term}%")
              ->orWhere('username', 'ilike', "%{$term}%")
              ->orWhere('id', 'ilike', "%{$term}%")
              ->orWhere('referrer', 'ilike', "%{$term}%");
        });

        $brands = $query->orderByDesc('created_at')->get();

        // Compute aggregates to mirror table values
        foreach ($brands as $brand) {
            $transactions = $brand->legacyUsers->flatMap->transactions;
            $brand->deposits_made = $transactions->where('PaymentDirection', 1)->where('Status', 7)->sum('Amount');
            $brand->withdrawals_made = $transactions->where('PaymentDirection', 0)->where('Status',7)->sum('Amount');
        }

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            'Registration Date', 'Brand Name', 'Username', 'Status', 'Registration Link', 'Referrer', 'Deposits Made', 'Withdrawals Made'
        ];

        $statusLabels = [
            1 => 'Active',
            2 => 'Suspended',
        ];

        $callback = function () use ($brands, $columns, $statusLabels) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($brands as $brand) {
                // Format date as Y-m-d H:i for export
                $date = $brand->created_at ?? $brand->CreatedAt ?? null;
                $formattedDate = $date ? \Carbon\Carbon::parse($date)->format('Y-m-d H:i') : 'N/A';
                $status = $statusLabels[$brand->status] ?? 'Unknown';
                $registrationLink = "https://user.orion-pay.ca/?ref=" . ($brand->ref_token ?? '');

                fputcsv($file, [
                    $formattedDate,
                    $brand->brand_name,
                    $brand->username,
                    $status,
                    $registrationLink,
                    $brand->referrer,
                    $brand->deposits_made,
                    $brand->withdrawals_made,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }



    public function render()
    {
        $brands = $this->getFilteredBrands();
        $this->dispatch('livewire-updated');

        return view('livewire.brand-management', [
                'brands' => $brands,
            ]);
    }
}
