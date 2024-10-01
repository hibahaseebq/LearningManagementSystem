<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserRequest;
use App\Http\Requests\StudentActionRequest;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    // Add a user (manager, admin, supervisor, student) by Admin
    public function addUser(AddUserRequest $request)
    {
        $validated = $request->validated();

        $result = $this->adminService->addUser($validated);

        if (!$result['success']) {
            return errorResponse($result['message'], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse($result['message'], [], \Illuminate\Http\Response::HTTP_CREATED);
    }

    // Approve a student
    public function approveStudent(StudentActionRequest $request, $id)
    {
        $student = User::findOrFail($id);
        $result = $this->adminService->approveStudent($student);

        if (!$result['success']) {
            return errorResponse($result['message'], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse($result['message'], [], \Illuminate\Http\Response::HTTP_OK);
    }

    // Reject a student
    public function rejectStudent(StudentActionRequest $request, $id)
    {
        $student = User::findOrFail($id);
        $result = $this->adminService->rejectStudent($student);

        if (!$result['success']) {
            return errorResponse($result['message'], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse($result['message'], [], \Illuminate\Http\Response::HTTP_OK);
    }

    // View all students
    public function viewStudents()
    {
        $result = $this->adminService->viewStudents();

        if (!$result['success']) {
            return errorResponse($result['message'], \Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        return successResponse($result['message'], $result['data'], \Illuminate\Http\Response::HTTP_OK);
    }
}
