<?php

namespace App\Mail;

use App\Models\RegistrationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class NewUserRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RegistrationRequest $request, public \App\Models\User $admin)
    {
    }

    public function build(): self
    {
        $approveUrl = URL::signedRoute('admin.registrations.approve', ['registrationRequest' => $this->request->id]);
        $declineUrl = URL::signedRoute('admin.registrations.decline', ['registrationRequest' => $this->request->id]);

        return $this->subject('New user registration request')
            ->view('emails.new-user-registration', [
                'user'       => $this->request,
                'admin'      => $this->admin,
                'approveUrl' => $approveUrl,
                'declineUrl' => $declineUrl,
            ]);
    }
}

