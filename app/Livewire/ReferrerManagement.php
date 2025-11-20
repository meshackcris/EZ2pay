<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Referrer;
use Livewire\WithPagination;
use App\Support\AppliesSearchTerms;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class ReferrerManagement extends Component
{

    use WithPagination;
    use AppliesSearchTerms;
    protected $paginationTheme = 'tailwind';

    public $perPage = 10;
    public $search = '';
    public array $status = [];
    public $startDate;
    public $endDate;
    protected $listeners = ['referrerAdded' => '$refresh'];

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

public function getFilteredRefs()
{
    $query = Referrer::with(['brands.legacyUsers.transactions']) // Eager load through brands
        ->select('referrer_name', 'username','email', 'id', 'created_at', 'status', 'ref_id');

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
        $q->where('referrer_name', 'ilike', "%{$term}%")
          ->orWhere('username', 'ilike', "%{$term}%")
          ->orWhere('email', 'ilike', "%{$term}%")
          ->orWhere('id', 'ilike', "%{$term}%");
    });

    $referrers = $query->latest('created_at')->paginate($this->perPage);

    $referrers->getCollection()->transform(function ($referrer) {
    $transactions = $referrer->brands
        ->flatMap->legacyUsers
        ->flatMap->transactions;

    $referrer->deposits_made = $transactions
        ->where('PaymentDirection', 1)
        ->sum('Amount');
    $referrer->withdrawals_made = $referrer->total_withdrawals;
    return $referrer;
});
    // Return the paginated collection

    return $referrers;
}



    public function render()
    {
        $referrers = $this->getFilteredRefs();
        $this->dispatch('livewire-updated');

        return view('livewire.referrer-management', [
                'referrers' => $referrers,
            ]);
    }

    public function exportToCSV(): StreamedResponse
    {
        $fileName = 'referrers_export_' . now()->format('Y_m_d_His') . '.csv';

        // Export all filtered referrers, ordered by registration date
        $query = Referrer::with(['brands.legacyUsers.transactions'])
            ->select('referrer_name', 'username','email', 'id', 'created_at', 'status', 'ref_id');

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
            $q->where('referrer_name', 'ilike', "%{$term}%")
              ->orWhere('username', 'ilike', "%{$term}%")
              ->orWhere('email', 'ilike', "%{$term}%")
              ->orWhere('id', 'ilike', "%{$term}%");
        });

        $referrers = $query->orderByDesc('created_at')->get();

        // Compute aggregates to mirror table values
        foreach ($referrers as $referrer) {
            $transactions = $referrer->brands
                ->flatMap->legacyUsers
                ->flatMap->transactions;
            $referrer->deposits_made = $transactions
                ->where('PaymentDirection', 1)
                ->sum('Amount');
            $referrer->withdrawals_made = $referrer->total_withdrawals;
        }

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            'Registration Date', 'Name', 'Username', 'Status', 'Deposits Made', 'Withdrawals Made'
        ];

        $statusLabels = [
            1 => 'Active',
            2 => 'Suspended',
        ];

        $callback = function () use ($referrers, $columns, $statusLabels) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($referrers as $referrer) {
                // Format date as Y-m-d H:i for export
                $date = $referrer->created_at ?? $referrer->CreatedAt ?? null;
                $formattedDate = $date ? \Carbon\Carbon::parse($date)->format('Y-m-d H:i') : 'N/A';
                $status = $statusLabels[$referrer->status] ?? 'Unknown';

                fputcsv($file, [
                    $formattedDate,
                    $referrer->referrer_name,
                    $referrer->username,
                    $status,
                    $referrer->deposits_made,
                    $referrer->withdrawals_made,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
