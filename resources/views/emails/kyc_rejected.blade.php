<x-emails.layout 
    :user="$user"
    :subject="'Your KYC Has Been Rejected - ' . config('app.name')"
    :title="'We Could Not Approve Your KYC Submission'"
    :body="'Unfortunately, your KYC submission did not meet our requirements. Please review your details and submit again.'"
    :button_url="'https://user.orion-pay.ca/dashboard'"
    :button_text="'Resubmit KYC'"
/>
