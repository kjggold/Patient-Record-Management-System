<?php

namespace App\Http\Controllers;

use App\Models\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationApprovedMail;

class AdminUserApprovalController extends Controller
{
    /**
     * Approve a registration request (via signed URL from email).
     */
    public function approve(Request $request, RegistrationRequest $registrationRequest)
    {
        // If this request was already processed/deleted, show a friendly message.
        if (!$registrationRequest->exists) {
            return view('auth.approval-result', [
                'title' => 'Already Processed',
                'message' => 'This registration request was already processed.',
                'status' => 'info',
            ]);
        }

        // Create user only on approve.
        $plainPassword = Crypt::decryptString($registrationRequest->encrypted_password);
        $user = User::create([
            'name'     => $registrationRequest->name,
            'email'    => $registrationRequest->email,
            'password' => $plainPassword, // hashed by cast
            'role'     => 'user',
            'status'   => 'active',
        ]);

        // Notify the user that their registration has been approved.
        Mail::to($user->email)->send(new RegistrationApprovedMail($user));

        // Remove request after processing
        $registrationRequest->delete();

        return view('auth.approval-result', [
            'title' => 'Registration Approved',
            'message' => "Registration successful for {$user->email}. The account is now created and the user can log in and access the dashboard.",
            'status' => 'success',
        ]);
    }

    /**
     * Decline a registration request (via signed URL from email).
     */
    public function decline(Request $request, RegistrationRequest $registrationRequest)
    {
        // Decline means: do not create user; remove the request from DB.
        $email = $registrationRequest->email;
        $registrationRequest->delete();

        return view('auth.approval-result', [
            'title' => 'Registration Declined',
            'message' => "Registration declined for {$email}. No user account was created.",
            'status' => 'warning',
        ]);
    }
}

