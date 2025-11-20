<x-emails.layout 
    :user="$user"
    :subject="'Your KYC Has Been Approved - ' . config('app.name')"
    :title="'Congratulations! Your KYC Has Been Approved'"
    :body="'We have reviewed and approved your KYC submission. You can now enjoy full access to our platform.'"
    :button_url="'https://user.orion-pay.ca/dashboard'"
    :button_text="'Go to Dashboard'"
/>
