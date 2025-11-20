<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\AptPayTransaction;
use App\Models\LegacyUser;
use Illuminate\Support\Carbon;



class DashboardController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public function index(Request $request): View
{
    // Read filters
    $type = $request->get('type', 'deposit');
    $range = $request->get('range', '1m');
    $PaymentDirection = $type === 'deposit' ? 1 : 3;

    // Chart date range
    $start = match ($range) {
        '24h' => Carbon::now()->subHours(23),
        '7d' => Carbon::now()->subDays(6),
        default => Carbon::now()->subDays(29),
    };

    // Cache basic dashboard summary for 30s
    $summary = cache()->remember('dashboard_summary', 30, function () {
        return [
            'pendingWithdrawals' => AptPayTransaction::where('PaymentDirection', 3)->where('Status', 3)->count(),
            'pendingDeposits' => AptPayTransaction::where('PaymentDirection', 1)->where('Status', 3)->count(),
            'failedWithdrawals' => AptPayTransaction::where('PaymentDirection', 3)->whereIn('Status', [9, 17])->count(),
            'failedDeposits' => AptPayTransaction::where('PaymentDirection', 1)->whereIn('Status', [9, 17])->count(),
            'totalUsers' => LegacyUser::count(),
            'totalDeposits' => AptPayTransaction::where('PaymentDirection', 1)->sum('Amount'),
            'totalWithdrawal' => AptPayTransaction::where('PaymentDirection', 3)->sum('Amount'),
        ];
    });

    // Chart data
    $raw = AptPayTransaction::where('PaymentDirection', $PaymentDirection)
        ->where('CreatedAt', '>=', $start)
        ->selectRaw("to_char(\"CreatedAt\", '" . ($range === '24h' ? 'HH24:00' : 'YYYY-MM-DD') . "') as label, SUM(\"Amount\") as total")
        ->groupBy('label')
        ->orderBy('label')
        ->pluck('total', 'label');

    $labels = [];
    $data = [];

    if ($range === '24h') {
        for ($i = 0; $i < 24; $i++) {
            $label = $start->copy()->addHours($i)->format('H:00');
            $labels[] = $label;
            $data[] = (float) ($raw[$label] ?? 0);
        }
    } else {
        $days = $range === '7d' ? 7 : 30;
        for ($i = 0; $i < $days; $i++) {
            $labelDate = $start->copy()->addDays($i);
            $label = $labelDate->format('M d');
            $lookupKey = $labelDate->format('Y-m-d');
            $labels[] = $label;
            $data[] = (float) ($raw[$lookupKey] ?? 0);
        }
    }

    // Optimize payment categorization (cache for 30s)
    $paymentStats = cache()->remember('dashboard_payment_stats', 30, function () {
        $payments = AptPayTransaction::where('Status', 7)->get();

        $total = $payments->whereIn('TransactionType',[6,1,5,4])->count();
        $meft = $payments->where('TransactionType', 6)->count();
        $eft = $payments->where('TransactionType', 1)->count();
        $mwire = $payments->where('TransactionType', 5)->count();
        $cc = $payments->where('TransactionType', 4)->count();

        return [
            'meftPercentage' => $total ? round(($meft / $total) * 100, 2) : 0,
            'eftPercentage' => $total ? round(($eft / $total) * 100, 2) : 0,
            'mwirePercentage' => $total ? round(($mwire / $total) * 100, 2) : 0,
            'ccPercentage' => $total ? round(($cc / $total) * 100, 2) : 0,
        ];
    });

    return view('dashboard', array_merge($summary, $paymentStats, [
        'labels' => $labels,
        'data' => $data,
        'type' => $type,
        'range' => $range,
    ]));
}

    public function paymentmanagement(): View
    {
        
        return view('payment-management');
    }
public function getFormattedDateAttribute()
{
    return Carbon::parse($this->CreatedAt)->format('M d, Y • h:i A');
}

 public function interacttransactions(): View
    {
        return view('interact-transactions');
    }
 public function ftdtransactions(): View
    {
        return view('ftd-transactions');
    }
 public function efttransactions(): View
    {
        return view('eft-transactions');
    }
     public function usermanagement(): View
    {
        return view('user-management');
    }
    public function brands(): View
    {
        return view('brands-management');
    }
    public function referrers(): View
    {
        return view('referrers-management');
    }
    public function kyc(): View
    {
        return view('kyc');
    }
    public function editbrand($id): View
    {
        return view('edit-brand', ['id' => $id]);
    }
    public function editreferrer($id): View
    {
        return view('edit-referrer', ['id' => $id]);
    }
    public function edituser($id): View
    {
        return view('edit-user', ['id' => $id]);
    }
public function withdrawals(): View
    {
        return view('withdrawals');
    }
public function refwithdrawals(): View
    {
        return view('refwithdrawals');
    }



}
