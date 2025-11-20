<x-emails.layout 
    :user="$user"
    :subject="'POP Submitted - ' . config('app.name')"
    :title="'A Client Has Submitted a POP'"
    :body="'
    <p>A client has submitted a Proof of Payment (POP) for their manual deposit.</p>

    <div style=\'text-align: left;\'>
        <p><strong>Name:</strong> ' . $transaction->legacyUser->FirstName . ' ' . $transaction->legacyUser->LastName . '</p>
        <p><strong>Email:</strong> ' . $transaction->legacyUser->Email . '</p>
        <p><strong>Amount:</strong> $' . number_format($transaction->Amount, 2) . '</p>
        <p><strong>Date Submitted:</strong> ' . $transaction->UpdatedAt->format('F j, Y h:i A') . '</p>
        <p><strong>Transaction Type:</strong> ' .
            ($transaction->TransactionType == 5 ? 'Manual Wire' : ($transaction->TransactionType == 6 ? 'Manual EFT' : 'Unknown')) . '</p>
        <p><strong>Reference Number ID:</strong> ' . $transaction->ReferenceNumber . '</p>
    </div>

    <p>Please review the submission and take the necessary actions in the admin dashboard.</p>
'"
/>
