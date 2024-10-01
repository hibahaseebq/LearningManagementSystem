<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\QuizAssignmentController;
use App\Http\Controllers\PasswordSetupController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizResultController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/user/set-password', [PasswordSetupController::class, 'setPassword']);

// User Routes
Route::middleware(['auth:api'])->group(function () {
    // Admin routes
    Route::post('/register/user', [AdminController::class, 'addUser']);

    // Student routes
    Route::post('/accept/submissions/{id}', [StudentController::class, 'approveStudent']);
    Route::post('/reject/submissions/{id}', [StudentController::class, 'rejectStudent']);
    Route::get('/get-submissions', [StudentController::class, 'viewSubmissions']);

    Route::get('/get-students', [StudentController::class, 'getAcceptedStudents']);

    // Route::get('/get-students', [StudentController::class, 'getAcceptedStudents']);

    // Route::get('/accepted-students', [StudentController::class, 'getAcceptedStudents']);

    // Quiz Routes
    Route::post('/create/quiz', [QuizController::class, 'store']);
    Route::put('/update-quiz/{id}', [QuizController::class, 'update']);
    Route::delete('/delete-quiz/{id}', [QuizController::class, 'destroy']);
    Route::post('/assign/quizzes', [QuizAssignmentController::class, 'assign']);
    Route::get('/get/student-assigned/quiz/{userId}', [QuizAssignmentController::class, 'getStudentAssignedQuizzes']);
    Route::post('/submit/quiz/options/{quizAssignmentId}', [QuizResultController::class, 'attemptQuiz']);
    Route::get('/quiz/assignments/{assignmentId}', [QuizAssignmentController::class, 'show']);
    Route::delete('/quiz/assignment/{id}', [QuizAssignmentController::class, 'destroy']);

    Route::get('/quiz-result/{quizAssignmentId}', [QuizResultController::class, 'showQuizResult']);


    // Video Routes
    Route::post('/upload-video', [VideoController::class, 'upload']);
    Route::get('/video/{id}', [VideoController::class, 'show']);
    Route::delete('/video/{id}', [VideoController::class, 'destroy']);
});

// Public Routes (student form submission)
Route::post('/submit', [StudentController::class, 'submitForm']);
Route::get('/get-quiz/{id}', [QuizController::class, 'show']);
Route::get('/get-quiz', [QuizController::class, 'index']);