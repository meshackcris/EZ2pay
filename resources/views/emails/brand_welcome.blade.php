<x-emails.layout 
    :user="$user"
    :subject="'Welcome to ' . config('app.name')"
    :title="'Welcome to Orion Pay – Let’s Get Started'"
    :body="'We are excited to welcome your brand on board! Your dashboard is now ready - explore it to manage your brand profile, monitor performance, and access powerful tools designed to help you grow.<br><br>Need help? Our dedicated support team is available 24/7 -  just reply to this email or visit our Help Center.'"
    :button_url="url('/dashboard')"
    :button_text="'Access Your Dashboard'"
/>
