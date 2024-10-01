<?php

namespace App\Http\Controllers;

use App\Models\StudentSubmission;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    // Handle student form submission
    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:student_submissions',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $result = $this->studentService->submitForm($validated);

        if (!$result['success']) {
            return errorResponse($result['message'], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse($result['message'], [], \Illuminate\Http\Response::HTTP_OK);
    }

    // Approve student and create user account
    public function approveStudent($id)
    {
        $submission = StudentSubmission::findOrFail($id);
        $result = $this->studentService->approveStudent($submission);

        if (!$result['success']) {
            return errorResponse($result['message'], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse($result['message'], [], \Illuminate\Http\Response::HTTP_OK);
    }

    // Reject student request
    public function rejectStudent($id)
    {
        $submission = StudentSubmission::findOrFail($id);
        $result = $this->studentService->rejectStudent($submission);

        if (!$result['success']) {
            return errorResponse($result['message'], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse($result['message'], [], \Illuminate\Http\Response::HTTP_OK);
    }

    // View all student submissions
    public function viewSubmissions()
    {
        $submissions = $this->studentService->viewSubmissions();

        if ($submissions->isEmpty()) {
            return errorResponse('No student submissions found', \Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        return successResponse('Student submissions retrieved successfully', $submissions, \Illuminate\Http\Response::HTTP_OK);
    }

    // Get all accepted students
    public function getAcceptedStudents(Request $request)
    {
        $students = $this->studentService->getAcceptedStudents();

        if ($students->isEmpty()) {
            return errorResponse('No accepted students found', \Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        // Return paginated response
        return successResponse('Students fetched successfully', $students);
    }
}