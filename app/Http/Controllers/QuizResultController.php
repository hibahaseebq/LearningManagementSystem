<?php

namespace App\Http\Controllers;

use App\Models\QuizAssignment;
use App\Models\Question; // Ensure you're importing the Question model from the correct namespace
use Illuminate\Http\Request;

class QuizResultController extends Controller
{
    // Store user's quiz attempt
    public function attemptQuiz(Request $request, $quizAssignmentId)
    {
        // Validate the request to ensure answers are provided for each question
        $validated = $request->validate([
            'answers' => 'required|array', // Array of question_id => selected_answer
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.selected_answer' => 'required|string',
        ]);

        // Retrieve the quiz assignment with related quiz and questions
        $assignment = QuizAssignment::with('quiz.questions')->find($quizAssignmentId);

        if (!$assignment) {
            return errorResponse('Quiz assignment not found', 404);
        }

        // Iterate through submitted answers and save selected answers for each question
        foreach ($validated['answers'] as $answerData) {
            $question = Question::find($answerData['question_id']);

            if ($question && $question->quiz_id === $assignment->quiz_id) {
                // Store selected answer for the quiz
                $question->update([
                    'selected_answer' => $answerData['selected_answer'],
                ]);
            }
        }

        // Optionally, you can update the status of the quiz assignment to mark it as 'attempted'
        $assignment->status = 'attempted';
        $assignment->save();

        return successResponse('Quiz attempted successfully', []);
    }

    // Calculate and store marks for a quiz assignment
    public function calculateMarks($quizAssignmentId)
    {
        // Retrieve the quiz assignment with related quiz and questions
        $assignment = QuizAssignment::with('quiz.questions')->find($quizAssignmentId);

        // Check if the assignment exists
        if (!$assignment) {
            return errorResponse('Quiz assignment not found', 404);
        }

        // Ensure the quiz has questions
        $questions = $assignment->quiz->questions;
        if ($questions->isEmpty()) {
            return errorResponse('No questions found for this quiz', 400);
        }

        $totalMarks = 0;
        $maxMarks = $assignment->quiz->total_marks; // Total possible marks for the quiz

        // Loop through the questions and compare selected answers with correct answers
        foreach ($questions as $question) {
            if ($question->selected_answer === $question->correct_answer) {
                // Add the marks if the answer is correct
                $totalMarks += $question->marks;
            }
        }

        // Update the QuizAssignment with the marks obtained and mark the quiz as completed
        $assignment->marks_obtained = $totalMarks;
        $assignment->status = 'completed';
        $assignment->save();

        return successResponse('Marks calculated successfully', [
            'total_marks' => $totalMarks,
            'max_marks' => $maxMarks
        ]);
    }

    public function showQuizResult($quizAssignmentId)
    {
        // Retrieve the quiz assignment along with related quiz and questions
        $assignment = QuizAssignment::with('quiz.questions')->find($quizAssignmentId);

        // Check if the assignment exists
        if (!$assignment) {
            return errorResponse('Quiz assignment not found', 404);
        }

        // Prepare the response data, including quiz name, marks, and answers
        $response = [
            'quiz_name' => $assignment->quiz->quiz_name,
            'marks_obtained' => $assignment->marks_obtained,
            'max_marks' => $assignment->quiz->total_marks,
            'questions' => $assignment->quiz->questions->map(function ($question) {
                return [
                    'question_id' => $question->id,
                    'question' => $question->question,
                    'options' => json_decode($question->options),
                    'selected_answer' => $question->selected_answer,
                    'correct_answer' => $question->correct_answer,
                ];
            }),
        ];

        return successResponse('Quiz result retrieved successfully', $response);
    }
}
