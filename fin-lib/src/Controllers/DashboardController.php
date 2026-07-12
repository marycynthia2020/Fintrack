<?php

namespace FinTrack\FinLib\Controllers;

use App\Http\Controllers\Controller;
use FinTrack\FinLib\Services\DashboardService;
use FinTrack\FinLib\Resources\DashboardSummaryResource;
use FinTrack\Core\Traits\ApiResponse;
use FinTrack\FinLib\Enums\Api;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function summary(Request $request)
    {
        $organizationId = $request->user()->organization_id;

        $summary = $this->dashboardService->getSummary($organizationId);

        return $this->success(new DashboardSummaryResource($summary), Api::Success->message());
    }
}
