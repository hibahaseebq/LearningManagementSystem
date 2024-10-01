<?php

namespace App\Services;

use App\Models\QuizAssignment;

class QuizAssignmentService
{
    /**
     * Assign a quiz to multiple users
     *
     * @param array $validated
     * @return array
     */
    public function assignQuizzes(array $validated)
    {
        $assignments = [];

        foreach ($validated['user_ids'] as $userId) {
            $assignmentData = [
                'quiz_id' => $validated['quiz_id'],
                'user_id' => $userId,
                'assigned_at' => $validated['assigned_at'],
                'activation_date' => $validated['activation_date'],
                'expiration_date' => $validated['expiration_date'],
            ];

            $assignments[] = QuizAssignment::create($assignmentData);
        }

        return $assignments;
    }

    /**
     * Get quizzes assigned to a user
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function getStudentAssignedQuizzes($userId)
    {
        return QuizAssignment::with('quiz.questions')
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * Get all quiz assignments for a user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserAssignments($userId)
    {
        return QuizAssignment::where('user_id', $userId)->get();
    }

    /**
     * Get specific quiz assignment
     *
     * @param int $assignmentId
     * @return QuizAssignment|null
     */
    public function getAssignment($assignmentId)
    {
        return QuizAssignment::find($assignmentId);
    }

    /**
     * Delete a quiz assignment
     *
     * @param int $assignmentId
     * @return bool
     */
    public function deleteAssignment($assignmentId)
{
    $assignment = QuizAssignment::find($assignmentId);
    
    if ($assignment) {
        $assignment->delete();
        return true;
    }

    // Log if assignment not found
    \Log::info('Assignment not found with id: ' . $assignmentId);
    
    return false;
}
}
