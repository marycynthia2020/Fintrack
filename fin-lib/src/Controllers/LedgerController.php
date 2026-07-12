<?php

namespace FinTrack\FinLib\Controllers;

use App\Http\Controllers\Controller;
use FinTrack\FinLib\Services\LedgerService;
use FinTrack\FinLib\Resources\LedgerResource;
use FinTrack\Core\Traits\ApiResponse;
use FinTrack\FinLib\Enums\Api;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected LedgerService $ledgerService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['type', 'event_type', 'created_by', 'start_date', 'end_date', 'per_page']);
        $filters['organization_id'] = $request->user()->organization_id;

        $ledgers = $this->ledgerService->list($filters);
        
        // Eager load relations
        $ledgers->load('createdBy', 'ledgerable');

        return $this->success(LedgerResource::collection($ledgers), Api::Success->message());
    }
}
