<p>Dear {{ $admin->name }},</p>

<p>A new user has registered and is waiting for your approval:</p>

<ul>
    <li><strong>Name:</strong> {{ $user->name }}</li>
    <li><strong>Email:</strong> {{ $user->email }}</li>
</ul>

<p>Please choose an action:</p>

<p>
    <a href="{{ $approveUrl }}">Approve this user</a> |
    <a href="{{ $declineUrl }}">Decline this user</a>
</p>

<p>Thank you.</p>
