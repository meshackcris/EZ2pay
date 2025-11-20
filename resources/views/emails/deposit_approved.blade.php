@php
    $types = [
        1 => 'Wire',
        2 => 'Interac',
        3 => 'Withdrawal',
        4 => 'Credit Card',
        5 => 'Manual Wire',
        6 => 'Manual EFT',
    ];
@endphp

<x-emails.layout 
    :user="$user"
    :subject="'Deposit Approved - ' . config('app.name')"
    :title="'Your Deposit Has Been Approved'"
    :body="'Your deposit of $' . number_format($transaction->Amount, 2) . ' via ' . ($types[$transaction->TransactionType] ?? 'Unknown') . ' has been approved by our team. You can now use your funds on the platform.'"
    :button_url="'https://user.orion-pay.ca/dashboard'"
    :button_text="'Go to Dashboard'"
/>
