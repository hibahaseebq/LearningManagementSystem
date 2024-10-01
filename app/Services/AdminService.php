<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\PasswordSetup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Notifications\RejectionNotification;
use Illuminate\Support\Facades\Log;

class AdminService
{
    // Add a user with dynamic role (admin, manager, supervisor, student)
    public function addUser(array $validated)
    {
        // Use provided password or generate a random one
        $password = $validated['password'] ?? Str::random(8);
        $passwordToken = Str::random(60);

        // Create a new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'remember_token' => $passwordToken,
        ]);

        // Find and assign the role dynamically based on the request
        $role = Role::findByName($validated['role'], 'web');
        if (!$role) {
            Log::error('Role ' . $validated['role'] . ' not found');
            return ['success' => false, 'message' => ucfirst($validated['role']) . ' role not found'];
        }

        // Assign the role to the user
        $user->assignRole($role);

        // Send password setup notification
        try {
            $user->notify(new PasswordSetup($passwordToken));
        } catch (\Exception $e) {
            Log::error('Failed to send password setup notification: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to send password setup notification'];
        }

        return ['success' => true, 'message' => ucfirst($validated['role']) . ' added successfully and notified'];
    }

    // Approve a student
    public function approveStudent(User $student)
    {
        $student->status = 'accepted';
        $student->save();

        $passwordToken = Str::random(60);

        try {
            $student->notify(new PasswordSetup($passwordToken));
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to send password setup notification'];
        }

        return ['success' => true, 'message' => 'Student approved and notified'];
    }

    // Reject a student
    public function rejectStudent(User $student)
    {
        $student->status = 'rejected';
        $student->save();

        try {
            $student->notify(new RejectionNotification());
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to send rejection notification'];
        }

        return ['success' => true, 'message' => 'Student rejected and notified'];
    }

    // View all students with the role 'student'
    public function viewStudents()
    {
        $students = User::role('student')->get();

        if ($students->isEmpty()) {
            return ['success' => false, 'message' => 'No students found'];
        }

        return ['success' => true, 'data' => $students, 'message' => 'Students retrieved successfully'];
    }
}
