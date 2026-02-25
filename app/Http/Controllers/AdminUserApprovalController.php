<?php

namespace App\Http\Controllers;

use App\Models\RegistrationRequest;
use App\Models\User;
use App\Models\AuthEvent;
use App\Mail\RegistrationApprovedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminUserApprovalController extends Controller
{
    public function approve(Request $request, $token)
    {
        try {
            // Find by token instead of ID
            $registrationRequest = RegistrationRequest::where('approval_token', $token)
                                                      ->where('status', 'pending')
                                                      ->first();

            if (!$registrationRequest) {
                return view('approval-result', [
                    'title' => 'Approval Failed',
                    'status' => 'error',
                    'message' => 'Registration request not found, already processed, or invalid token.'
                ]);
            }

            // Decrypt the password
            $decryptedPassword = Crypt::decryptString($registrationRequest->encrypted_password);

            // Create the user
            $user = User::create([
                'name' => $registrationRequest->name,
                'email' => $registrationRequest->email,
                'password' => Hash::make($decryptedPassword),
                'role' => 'user',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            // Send approval email to the user
            Mail::to($user->email)->send(new RegistrationApprovedMail($user));

            // Update registration request status
            $registrationRequest->update([
                'status' => 'approved',
                'approval_token' => null, // Clear the token
            ]);

            // Log the approval
            AuthEvent::create([
                'event_type' => 'registration_approved',
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'success' => true,
                'meta' => ['registration_id' => $registrationRequest->id]
            ]);

            return view('approval-result', [
                'title' => 'Registration Approved',
                'status' => 'success',
                'message' => "Registration successful for {$user->email}. The account is now created and the user can log in and access the dashboard."
            ]);

        } catch (\Exception $e) {
            Log::error('Registration approval failed: ' . $e->getMessage());

            return view('approval-result', [
                'title' => 'Approval Failed',
                'status' => 'error',
                'message' => 'An error occurred while processing the approval. Please contact support.'
            ]);
        }
    }

    public function decline(Request $request, $token)
    {
        try {
            // Find by token instead of ID
            $registrationRequest = RegistrationRequest::where('approval_token', $token)
                                                      ->where('status', 'pending')
                                                      ->first();

            if (!$registrationRequest) {
                return view('approval-result', [
                    'title' => 'Decline Failed',
                    'status' => 'error',
                    'message' => 'Registration request not found, already processed, or invalid token.'
                ]);
            }

            // Update registration request status
            $registrationRequest->update([
                'status' => 'declined',
                'approval_token' => null,
            ]);

            // Log the decline
            AuthEvent::create([
                'event_type' => 'registration_declined',
                'user_id' => null,
                'email' => $registrationRequest->email,
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'success' => true,
                'meta' => ['registration_id' => $registrationRequest->id]
            ]);

            return view('approval-result', [
                'title' => 'Registration Declined',
                'status' => 'warning',
                'message' => "Registration for {$registrationRequest->email} has been declined."
            ]);

        } catch (\Exception $e) {
            Log::error('Registration decline failed: ' . $e->getMessage());

            return view('approval-result', [
                'title' => 'Decline Failed',
                'status' => 'error',
                'message' => 'An error occurred while processing the decline.'
            ]);
        }
    }
}