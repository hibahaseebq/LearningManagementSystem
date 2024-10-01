<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\AddUserRequest;

class AuthController extends Controller
{
    protected $authService;

    // Inject the AuthService into the controller
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Login and generate JWT token
    public function login(LoginRequest $request) // Use LoginRequest for validation
    {
        $credentials = $request->only('email', 'password');

        // Call the AuthService for login
        return $this->authService->login($credentials);
    }

    // Logout the user and invalidate the token
    public function logout()
    {
        return $this->authService->logout();
    }

    // Fetch the authenticated user
    public function me()
    {
        return $this->authService->getAuthenticatedUser();
    }

    // Add a user (admin, manager, supervisor, student)
    public function addUser(AddUserRequest $request) // Use AddUserRequest for validation
    {
        $validated = $request->validated();

        // Call the AdminService to add a user
        $result = $this->authService->addUser($validated);

        if (!$result['success']) {
            return errorResponse($result['message'], 500);
        }

        return successResponse($result['message'], [], 201);
    }
}
