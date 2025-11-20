<x-emails.layout 
    :user="$user"
    :subject="'Your Proof of Payment Has Been Submitted - ' . config('app.name')"
    :title="'POP Received - Pending Review'"
    :body="
        '<p>We have received your Proof of Payment (POP) for your manual deposit.</p>

        <p>Below are the details of your submission:</p>
        <div style=\'text-align:left;\'>
        <p><strong>Name:</strong> ' . $transaction->legacyUser->FirstName . ' ' . $transaction->legacyUser->LastName . '</p>
        <p><strong>Email:</strong> ' . $transaction->legacyUser->Email . '</p>
        <p><strong>Amount:</strong> $' . number_format($transaction->Amount, 2) . '</p>
        <p><strong>Date Submitted:</strong> ' . $transaction->UpdatedAt->format('F j, Y h:i A') . '</p>
        <p><strong>Transaction Type:</strong> ' . 
            ($transaction->TransactionType == 5 
                ? 'Manual Wire' 
                : ($transaction->TransactionType == 6 
                    ? 'Manual EFT' 
                    : 'Unknown')) . '</p>
        <p><strong>Reference Number ID:</strong> ' . $transaction->ReferenceNumber . '</p>
        </div>
        <p>Our team will review your payment shortly. Once approved, you will receive a confirmation email and the funds will reflect in your account.</p>

        <p>Thank you for using ' . config('app.name') . '.</p>'
    "
/>
