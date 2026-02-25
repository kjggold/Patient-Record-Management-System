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
        // Make sure the token exists
        if (!$this->request->approval_token) {
            throw new \Exception('Approval token is missing for registration #' . $this->request->id);
        }

        $approveUrl = URL::signedRoute('admin.registrations.approve', ['token' => $this->request->approval_token]);
        $declineUrl = URL::signedRoute('admin.registrations.decline', ['token' => $this->request->approval_token]);

        return $this->subject('New user registration request')
            ->view('emails.new-user-registration', [
                'user'       => $this->request,
                'admin'      => $this->admin,
                'approveUrl' => $approveUrl,
                'declineUrl' => $declineUrl,
            ]);
    }
}