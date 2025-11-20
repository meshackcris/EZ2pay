<x-emails.layout 
    :user="$user"
    :subject="'Withdrawal Successful - ' . config('app.name')"
    :title="'Your Withdrawal Has Been Successfully Processed'"
    :body="'We have successfully processed your withdrawal request. Please check your account to confirm receipt.'"
    :button_url="'https://user.orion-pay.ca/dashboard'"
    :button_text="'View Transaction History'"
/>
