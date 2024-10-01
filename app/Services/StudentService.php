<?php

namespace App\Services;

use App\Models\StudentSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StudentSubmissionNotification;
use App\Notifications\PasswordSetup;
use App\Notifications\RejectionNotification;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StudentService
{
    // Method to handle student form submission
    public function submitForm(array $validated)
    {
        $cvPath = $validated['cv']->store('cv_uploads', 'public');

        $submission = StudentSubmission::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cv_path' => $cvPath,
            'status' => 'Pending',
        ]);

        Notification::send(User::role('admin')->get(), new StudentSubmissionNotification($submission));

        return ['success' => true, 'message' => 'Student form submitted successfully, awaiting approval'];
    }

    // Method to approve student and create user
    public function approveStudent(StudentSubmission $submission)
    {
        $student = User::create([
            'name' => $submission->name,
            'email' => $submission->email,
            'password' => Hash::make(Str::random(8)),
        ]);

        $studentRole = Role::findByName('student', 'web');
        if (!$studentRole) {
            return ['success' => false, 'message' => 'Student role not found'];
        }

        $student->assignRole($studentRole);

        $passwordToken = Str::random(60);
        try {
            $student->notify(new PasswordSetup($passwordToken));
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to send password setup notification'];
        }

        $submission->status = 'Accepted';
        $submission->save();

        return ['success' => true, 'message' => 'Student approved, user account created, and password setup link sent'];
    }

    // Method to reject student
    public function rejectStudent(StudentSubmission $submission)
    {
        $submission->status = 'Rejected';
        $submission->save();

        try {
            Notification::route('mail', $submission->email)->notify(new RejectionNotification());
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to send rejection notification'];
        }

        return ['success' => true, 'message' => 'Student rejected and notified'];
    }

    // Method to view all student submissions
    public function viewSubmissions()
    {
        return StudentSubmission::all();
    }

    // Method to retrieve all accepted students
    public function getAcceptedStudents()
    {

        return User::whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })->paginate(5); // paginate with 5 users per page
    }
}