<?php

namespace FinTrack\FinLib\Controllers;

use App\Http\Controllers\Controller;
use FinTrack\FinLib\Services\AccountService;
use FinTrack\FinLib\Resources\AccountResource;
use FinTrack\Core\Traits\ApiResponse;
use FinTrack\FinLib\Enums\Api;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AccountService $accountService
    ) {}

    public function balance(Request $request)
    {
        $organizationId = $request->user()->organization_id;

        $account = $this->accountService->find($organizationId);

        return $this->success(new AccountResource($account), Api::Success->message());
    }
}
