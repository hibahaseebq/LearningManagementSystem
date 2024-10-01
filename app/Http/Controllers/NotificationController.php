<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * List notifications for a user.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($userId)
    {
        $result = $this->notificationService->listNotifications($userId);

        if (!$result['success']) {
            return errorResponse($result['message'], \Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        return successResponse('Notifications retrieved successfully', $result['data']);
    }

    /**
     * Mark a notification as read.
     *
     * @param int $notificationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($notificationId)
    {
        $result = $this->notificationService->markAsRead($notificationId);

        if (!$result['success']) {
            return errorResponse($result['message'], \Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        return successResponse($result['message']);
    }
}
