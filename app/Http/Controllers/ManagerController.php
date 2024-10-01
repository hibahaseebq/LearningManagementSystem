<?php

namespace App\Http\Controllers;

use App\Models\QuizAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    // Assign Quiz to Student
    public function assignQuiz(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'student_id' => 'required|exists:users,id',
        ]);

        $assignment = QuizAssignment::create([
            'quiz_id' => $validated['quiz_id'],
            'user_id' => $validated['student_id'],
            'assigned_at' => now(),
            'activation_date' => now()->addDays(2), // Quiz activation 2 days later
            'expiration_date' => now()->addDays(7), // Quiz expires in 7 days
        ]);

        // Notify the student about the quiz assignment
        User::find($validated['student_id'])->notify(new QuizAssignedNotification());

        return response()->json(['message' => 'Quiz assigned successfully'], 201);
    }

    // View all students
    public function viewStudents()
    {
        $students = User::role('Student')->get();
        return response()->json($students);
    }
}
