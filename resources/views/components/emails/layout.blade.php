
@props([
    'user' => null,
    'subject' => null,
    'title' => null,
    'body' => null,
    'button_url' => null,
    'button_text' => 'Take Action',
    'below_body' => null,
])
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Notification' }}</title>
    <style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 40px 0; }
    .authincation { display: flex; justify-content: center; align-items: center; height: 100%; }
    .authincation-content {width: 600px; margin: auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .auth-form { padding: 30px; text-align: center; }
    .auth-form h4 { margin-bottom: 20px; }
    .auth-form p { margin-bottom: 20px; line-height: 1.6; }
    .btn {
        display: inline-block;
        padding: 12px 25px;
        color: #fff;
        background-color: #387a42; /* Green like your login button */
        border: none;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s ease;
        margin-top: 20px;
    }
    .btn:hover {
        background-color: #2e6637; /* Slightly darker hover */
    }
    
    .text-white{
        color: #fff !important;
    }
    .footer { margin-top: 20px; font-size: 12px; color: #999; }
</style>

</head>
<body>
    <div class="authincation">
        <div class="authincation-content">
            <div class="auth-form">
                <div class="text-center mb-3">
                    <img src="{{ asset('images/logo-dark.png') }}" alt="{{ config('app.name') }} Logo">
                </div>
                <h4>{{ $title ?? 'Hello ' . ($user->FirstName ?? 'User') }}</h4>
                <p>{!! $body ?? 'Notification content goes here.' !!}</p>
                @isset($button_url)
<a href="{{ $button_url }}" class="btn text-white">{{ $button_text ?? 'Take Action' }}</a>
                @endisset
                <p>{!! $below_body ?? '' !!}</p>
                <div class="footer">
                    <p><strong>Orion Pay | Secure Non-Custodial Payments </strong></p>
                    <p> Simplified, Safe, and Compliant.</p>
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    <p> <a href="https://orion-pay.ca/privacy-policy">Privacy Policy</a> | <a href="https://orion-pay.ca/terms-and-conditions/">Terms of Service</a> | <a href="https://orion-pay.ca/contact-us/">Help Center</a> </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
