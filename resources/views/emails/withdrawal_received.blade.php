<x-emails.layout 
    :user="$user"
    :subject="'Action Required: Confirm Receipt of Your Funds - ' . config('app.name')"
    :body="'Your recent payout of $'. $withdrawal->Amount.' has been successfully sent to your wallet.<br>
For compliance and security purposes, please confirm that you have received the funds.
<br><br>
To stay compliant, confirmation is required within 24 hours.'"
    :below_body="'Once confirmed, your transaction record will be marked as complete.
<br><br>
Thank you for your quick response and cooperation.'"
    :button_url="$confirmation_url"
    :button_text="'Confirm Withdrawal'"
/>
