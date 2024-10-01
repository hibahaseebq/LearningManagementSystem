<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // This is the missing import
use App\Models\User;
use App\Notifications\PasswordSetup;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        // Validate the incoming request
        $request->validate(['email' => 'required|email']);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Generate a random token for the password reset
        $token = Str::random(60);  // The Str class is now properly imported

        // Store the token in the password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email], // Match on email
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Send the Password Setup notification with the token
        $user->notify(new PasswordSetup($token));

        return response()->json(['message' => 'Password setup link sent successfully'], 200);
    }
}
