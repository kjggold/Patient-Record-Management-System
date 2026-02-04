<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class NewUserRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public User $admin)
    {
    }

    public function build(): self
    {
        $approveUrl = URL::signedRoute('admin.users.approve', ['user' => $this->user->id]);
        $declineUrl = URL::signedRoute('admin.users.decline', ['user' => $this->user->id]);

        return $this->subject('New user registration request')
            ->view('emails.new-user-registration', [
                'user'       => $this->user,
                'admin'      => $this->admin,
                'approveUrl' => $approveUrl,
                'declineUrl' => $declineUrl,
            ]);
    }
}

