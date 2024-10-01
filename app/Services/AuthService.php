<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Notifications\PasswordSetup;

class AuthService
{
    /**
     * Handle the login process
     *
     * @param array $credentials
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(array $credentials)
    {
        // Attempt to authenticate the user and generate a JWT token
        if (!$token = JWTAuth::attempt($credentials)) {
            return errorResponse('Unauthorized', 401);
        }

        // Return the response with the generated token and user details
        return $this->respondWithToken($token);
    }

    /**
     * Handle the logout process
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            // Invalidate the token
            JWTAuth::invalidate(JWTAuth::getToken());

            return successResponse('Successfully logged out');
        } catch (\Exception $e) {
            Log::error('Failed to logout: ' . $e->getMessage());
            return errorResponse('Logout failed', 500);
        }
    }

    /**
     * Fetch the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser()
    {
        $user = Auth::user();
        return successResponse('User information retrieved successfully', $user);
    }

    /**
     * Add a new user (Admin, Manager, Student, etc.)
     *
     * @param array $data
     * @return array
     */
    public function addUser(array $data)
    {
        $role = $data['role'];
        $password = $data['password'] ?? Str::random(8); // Generate password if not provided

        try {
            // Create a new user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($password),
            ]);

            // Assign role to the user
            $user->assignRole($role);

            // Send password setup notification
            $token = Str::random(60);
            $user->notify(new PasswordSetup($token)); // Assuming you have a PasswordSetup notification

            return ['success' => true, 'message' => ucfirst($role) . ' added successfully'];
        } catch (\Exception $e) {
            Log::error('Failed to add user: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to add user.'];
        }
    }

    /**
     * Format the token response with additional user details (roles and permissions)
     *
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = Auth::user();
        
        // Retrieve roles and permissions for the authenticated user
        $roles = $user->getRoleNames(); // Fetch roles
        $permissions = $user->getAllPermissions(); // Fetch permissions

        return successResponse('Login successful', [
            'access_token' => $token,  // Return JWT token
            'role' => $roles->first(),  // Assuming the user has only one role
            'permissions' => $permissions->pluck('name')->toArray(),  // Return permission names
        ]);
    }

    /**
     * Resend password setup email (for expired or lost tokens)
     *
     * @param User $user
     * @return array
     */
    public function resendPasswordSetupEmail(User $user)
    {
        try {
            $token = Str::random(60); // Generate a new token

            // Send the password setup email notification
            $user->notify(new PasswordSetup($token));

            return ['success' => true, 'message' => 'Password setup email resent successfully'];
        } catch (\Exception $e) {
            Log::error('Failed to resend password setup email: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to resend password setup email.'];
        }
    }
}
