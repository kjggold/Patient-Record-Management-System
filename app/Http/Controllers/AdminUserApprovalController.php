<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserApprovalController extends Controller
{
    /**
     * Approve a pending user (via signed URL from email).
     */
    public function approve(Request $request, User $user)
    {
        // Only main admin should use this link
        // (link is signed, but we also ensure we don't re-approve)
        if ($user->status !== 'pending') {
            return redirect()->route('login')->with('status', 'User already processed.');
        }

        $user->update(['status' => 'active']);

        return redirect()->route('login')->with('status', "User {$user->email} approved.");
    }

    /**
     * Decline a pending user (via signed URL from email).
     */
    public function decline(Request $request, User $user)
    {
        if ($user->status !== 'pending') {
            return redirect()->route('login')->with('status', 'User already processed.');
        }

        $user->update(['status' => 'rejected']);

        return redirect()->route('login')->with('status', "User {$user->email} declined.");
    }
}

