<?php

namespace FinTrack\FinLib\Controllers;

use App\Http\Controllers\Controller;
use FinTrack\FinLib\Models\Income;
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

    protected IncomeService $incomeService;

    /**
     * Create a new controller instance.
     */
    public function __construct(IncomeService $incomeService)
    {
        $this->incomeService = $incomeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['type', 'created_by', 'start_date', 'end_date', 'per_page']);
        $filters['organization_id'] = $request->user()->organization_id;

        $incomes = $this->incomeService->list($filters);

        $mappedIncomes = $incomes->map(function ($income) {
            return new IncomeResource($income);
        });

        return $this->success($mappedIncomes, Api::Success->message());
    }

    /**
     * Store a newly created resource in storage.
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

        return $this->success(new IncomeResource($income), Api::Success->message());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIncomeRequest $request, string $id)
    {
        $income = $this->incomeService->find($id);
        $data = $request->validated();

        $updatedIncome = $this->incomeService->update($income, $data);

        return $this->success(new IncomeResource($updatedIncome), Api::Success->message());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $income = $this->incomeService->find($id);

        $this->incomeService->delete($income);

        return $this->success(null, Api::Success->message());
    }
}
