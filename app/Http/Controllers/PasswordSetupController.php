<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetPasswordRequest;
use App\Services\PasswordSetupService;

class PasswordSetupController extends Controller
{
    protected $passwordSetupService;

    public function __construct(PasswordSetupService $passwordSetupService)
    {
        $this->passwordSetupService = $passwordSetupService;
    }

    public function setPassword(SetPasswordRequest $request)
    {
        $data = $request->validated();
        $result = $this->passwordSetupService->setPassword($data);

        if (!$result['success']) {
            return errorResponse($result['message'], $result['status']);
        }

        return successResponse($result['message'], [], $result['status']);
    }
}
