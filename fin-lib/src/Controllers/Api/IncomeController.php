<?php

namespace FinTrack\FinLib\Controllers\Api;

use App\Http\Controllers\Controller;
use FinTrack\FinLib\Services\IncomeService;
use FinTrack\FinLib\Requests\StoreIncomeRequest;
use FinTrack\FinLib\Requests\UpdateIncomeRequest;
use FinTrack\FinLib\Resources\IncomeResource;
use FinTrack\Core\Traits\ApiResponse;
use FinTrack\FinLib\Enums\Api;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected IncomeService $incomeService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['type', 'created_by', 'start_date', 'end_date', 'per_page']);
        $filters['organization_id'] = $request->user()->organization_id;

        $incomes = $this->incomeService->list($filters);
        $incomes->load('createdBy', 'updatedBy');

        return $this->success(IncomeResource::collection($incomes), Api::Success->message());
    }

    /**
     * Store a newly created resource.
     */
    public function store(StoreIncomeRequest $request)
    {
        $data = $request->validated();
        $data['organization_id'] = $request->user()->organization_id;
        $data['created_by'] = $request->user()->id;

        $income = $this->incomeService->create($data);

        return $this->created(new IncomeResource($income), Api::Created->message());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $income = $this->incomeService->find($id);
        $income->load('createdBy', 'updatedBy');

        return $this->success(new IncomeResource($income), Api::Success->message());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIncomeRequest $request, string $id)
    {
        $income = $this->incomeService->find($id);

        if ($income->created_by !== $request->user()->id) {
            abort(403, 'Only the creator of this income can update it.');
        }

        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        $updatedIncome = $this->incomeService->update($income, $data);
         $income->load('createdBy', 'updatedBy');
        return $this->success(new IncomeResource($updatedIncome), Api::Success->message());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $income = $this->incomeService->find($id);

        if ($income->created_by !== $request->user()->id) {
            abort(403, 'Only the creator of this income can delete it.');
        }

        $this->incomeService->delete($income);

        return $this->success(null, Api::Success->message());
    }

    public function categories()
    {
        return $this->success(
            $this->incomeService->categories()
        );
    }
}
