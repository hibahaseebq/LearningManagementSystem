<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizAssignmentRequest;
use App\Services\QuizAssignmentService;

class QuizAssignmentController extends Controller
{
    protected $quizAssignmentService;

    public function __construct(QuizAssignmentService $quizAssignmentService)
    {
        $this->quizAssignmentService = $quizAssignmentService;
    }

    // Assign a quiz to multiple users
    public function assign(QuizAssignmentRequest $request)
    {
        $validated = $request->validated();

        $assignments = $this->quizAssignmentService->assignQuizzes($validated);

        return successResponse('Quiz assigned to users successfully', ['assignments' => $assignments], 201);
    }

    // Get quizzes assigned to a user along with their questions, selected answers, and correct answers
    public function getStudentAssignedQuizzes($userId)
    {
        $assignedQuizzes = $this->quizAssignmentService->getStudentAssignedQuizzes($userId);

        if ($assignedQuizzes->isEmpty()) {
            return errorResponse('No quizzes assigned to this student', 404);
        }

        // Prepare response with quiz questions, selected answers, and correct answers
        $response = $assignedQuizzes->map(function ($assignment) {
            if ($assignment->quiz) {
                return [
                    'quiz_id' => $assignment->quiz_id,
                    'quiz_name' => $assignment->quiz->quiz_name ?? 'Unknown Quiz',
                    'questions' => $assignment->quiz->questions->map(function ($question) {
                        return [
                            'question_id' => $question->id,
                            'question' => $question->question,
                            'options' => json_decode($question->options),
                            'selected_answer' => $question->selected_answer,
                            'correct_answer' => $question->correct_answer,
                        ];
                    }),
                    'marks_obtained' => $assignment->marks_obtained,
                    'status' => $assignment->status,
                ];
            } else {
                return [
                    'quiz_id' => $assignment->quiz_id,
                    'quiz_name' => 'Quiz not found',
                    'questions' => [],
                    'marks_obtained' => $assignment->marks_obtained,
                    'status' => $assignment->status,
                ];
            }
        });

        return successResponse('Assigned quizzes retrieved successfully', $response);
    }

    // List all quiz assignments for a user
    public function index($userId)
    {
        $assignments = $this->quizAssignmentService->getUserAssignments($userId);

        return successResponse('Assignments retrieved successfully', $assignments);
    }

    // Show a specific quiz assignment
    public function show($assignmentId)
    {
        $assignment = $this->quizAssignmentService->getAssignment($assignmentId);

        if (!$assignment) {
            return errorResponse('Assignment not found', 404);
        }

        return successResponse('Assignment retrieved successfully', $assignment);
    }

    // Delete a quiz assignment
    public function destroy($id)
    {
        $deleted = $this->quizAssignmentService->deleteAssignment($id);

        if (!$deleted) {
            return errorResponse('Assignment not found', 404);
        }

        return successResponse('Assignment deleted successfully');
    }
}
