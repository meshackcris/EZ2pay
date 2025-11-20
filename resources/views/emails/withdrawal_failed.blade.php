<x-emails.layout 
    :user="$user"
    :subject="'Withdrawal Failed - ' . config('app.name')"
    :title="'Your Withdrawal Could Not Be Processed'"
    :body="'Unfortunately, your withdrawal request could not be completed. Please contact our support team for further assistance.'"
    :button_url="'https://user.orion-pay.ca/dashboard'"
    :button_text="'Contact Support'"
/>
