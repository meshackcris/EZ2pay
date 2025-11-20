<x-emails.layout 
    :user="$user"
    :subject="'Welcome to ' . config('app.name')"
    :title="'Welcome to Orion Pay – Let’s Get Started'"
:body="'We are thrilled to welcome you to our Referral Program!<br><br>Log in to your dashboard to track your referrals, monitor your rewards, and manage your account with ease.<br><br>
Need help? Our support team is available 24/7 -  just reply to this email or visit our Help Center.'"
    :button_url="url('/dashboard')"
    :button_text="'Access Your Dashboard'"
/>
