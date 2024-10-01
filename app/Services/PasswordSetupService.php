<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class PasswordSetupService
{
    /**
     * Handle setting up a new password for a user.
     *
     * @param array $data
     * @return array
     */
    public function setPassword(array $data)
    {
        // Find the user by email
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return ['success' => false, 'message' => 'User not found', 'status' => 404];
        }

        // Check if the user already has a token in the password_resets table
        $existingReset = DB::table('password_resets')->where('email', $data['email'])->first();

        $token = $existingReset->token ?? Str::random(60);
        if (!$existingReset) {
            // Insert the token into password_resets table
            DB::table('password_resets')->updateOrInsert(
                ['email' => $data['email']],
                [
                    'token' => $token,
                    'created_at' => now(),
                ]
            );
        } else {
            // Validate token expiration
            $tokenExpirationTime = Carbon::parse($existingReset->created_at)->addMinutes(60);
            if (Carbon::now()->greaterThan($tokenExpirationTime)) {
                return ['success' => false, 'message' => 'Token has expired', 'status' => 410];
            }
        }

        // Ensure the new password isn't the same as the old one
        if (Hash::check($data['password'], $user->password)) {
            return ['success' => false, 'message' => 'The new password cannot be the same as the old password', 'status' => 400];
        }

        // Update the user's password
        $user->password = Hash::make($data['password']);
        $user->save();

        // Check if the password was successfully updated
        if (!Hash::check($data['password'], $user->password)) {
            return ['success' => false, 'message' => 'Failed to update password', 'status' => 500];
        }

        // Delete the token from password_resets table
        DB::table('password_resets')->where('email', $data['email'])->delete();

        return ['success' => true, 'message' => 'Password set successfully', 'status' => 200];
    }
}
