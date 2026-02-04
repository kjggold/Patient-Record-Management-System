<p>Dear {{ $user->name }},</p>

<p>Your registration for MediCore Clinic has been <strong>approved</strong>.</p>

<p>You can now log in and access your dashboard using the email address:</p>
<p><strong>{{ $user->email }}</strong></p>

<p>
    <a href="{{ url('/login') }}">Tap here to log in</a>
    (works on desktop and mobile browsers).
</p>

<p>Thank you.</p>

