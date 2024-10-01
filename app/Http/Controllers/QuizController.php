<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    // List all quizzes
    public function index()
    {
        $quizzes = Quiz::with('questions')->get(); // Load quizzes with questions
        return successResponse('Quizzes retrieved successfully', $quizzes);
    }

    // Show details of a single quiz
    public function show($id)
    {
        $quiz = Quiz::with('questions')->find($id); // Load quiz with questions
        if (!$quiz) {
            return errorResponse('Quiz not found', 404);
        }

        return successResponse('Quiz retrieved successfully', $quiz);
    }

    // Create a new quiz
  public function store(Request $request)
{
    $validated = $request->validate([
        'quiz_name' => 'required|string|max:255',
        'total_marks' => 'required|integer',
        'duration' => 'required|integer', // Duration in minutes
        // 'starts_at' => 'date',
        // 'ends_at' => 'date',
        'questions' => 'required|array', 
        'questions.*.question' => 'required|string', 
        'questions.*.options' => 'required|array|min:2', 
        'questions.*.correct_answer' => 'required|string' 
    ]);

    // Start a DB transaction to ensure data integrity
    DB::beginTransaction();
    try {
        // Create the quiz
        $quiz = Quiz::create([
            'quiz_name' => $validated['quiz_name'],
            'total_marks' => $validated['total_marks'],
            'duration' => $validated['duration'],
            // 'starts_at' => $validated['starts_at'],
            // 'ends_at' => $validated['ends_at'],
        ]);

        // Loop through the questions and save each one
        foreach ($validated['questions'] as $questionData) {
            Question::create([
                'quiz_id' => $quiz->id,
                'question' => $questionData['question'],
                'options' => json_encode($questionData['options']), // Store options as JSON
                'correct_answer' => $questionData['correct_answer'],
            ]);
        }

        // Commit the transaction
        DB::commit();

        return successResponse('Quiz created successfully', ['quiz' => $quiz->load('questions')], 201);
    } catch (\Exception $e) {
        // Rollback the transaction if there's any error
        DB::rollBack();
        
        // Return the error message for debugging purposes
        return errorResponse('Failed to create quiz: ' . $e->getMessage(), 500);
    }
}

    // Update an existing quiz
    public function update(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return errorResponse('Quiz not found', 404);
        }

        $validated = $request->validate([
            'quiz_name' => 'string|max:255',
            'total_marks' => 'integer',
            'duration' => 'integer', // Duration in minutes
            'description' => 'nullable|string',
            // 'starts_at' => 'nullable|date',
            // 'ends_at' => 'nullable|date',
            'questions' => 'nullable|array',
            'questions.*.question' => 'required_with:questions|string',
            'questions.*.options' => 'required_with:questions|array|min:2',
            'questions.*.correct_answer' => 'required_with:questions|string',
        ]);

        DB::beginTransaction();
        try {
            // Update the quiz details
            $quiz->update($validated);

            // If there are new questions, update them
            if (isset($validated['questions'])) {
                // Delete existing questions if they are updated
                $quiz->questions()->delete();

                // Add new questions
                foreach ($validated['questions'] as $questionData) {
                    Question::create([
                        'quiz_id' => $quiz->id,
                        'question' => $questionData['question'],
                        'options' => json_encode($questionData['options']),
                        'correct_answer' => $questionData['correct_answer'],
                    ]);
                }
            }

            DB::commit();

            return successResponse('Quiz updated successfully', ['quiz' => $quiz->load('questions')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return errorResponse('Failed to update quiz', 500);
        }
    }

    // Delete a quiz
    public function destroy($id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return errorResponse('Quiz not found', 404);
        }

        $quiz->delete();
        return successResponse('Quiz deleted successfully');
    }
}
