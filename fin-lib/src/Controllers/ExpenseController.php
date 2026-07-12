<?php

namespace FinTrack\FinLib\Controllers;

use App\Http\Controllers\Controller;
use FinTrack\FinLib\Models\Expense;
use FinTrack\FinLib\Services\ExpenseService;
use FinTrack\FinLib\Requests\StoreExpenseRequest;
use FinTrack\FinLib\Requests\UpdateExpenseRequest;
use FinTrack\FinLib\Resources\ExpenseResource;
use FinTrack\Core\Traits\ApiResponse;
use FinTrack\FinLib\Enums\Api;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ExpenseService $expenseService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['type', 'created_by', 'start_date', 'end_date', 'per_page']);
        $filters['organization_id'] = $request->user()->organization_id;

        $expenses = $this->expenseService->list($filters);
        $expenses->load('createdBy', 'updatedBy');

        return $this->success(ExpenseResource::collection($expenses), Api::Success->message());
    }

    /**
     * Store a newly created resource.
     */
    public function store(StoreExpenseRequest $request)
    {
        $data = $request->validated();
        $data['organization_id'] = $request->user()->organization_id;
        $data['created_by'] = $request->user()->id;

        $expense = $this->expenseService->create($data);

        return $this->created(new ExpenseResource($expense), Api::Created->message());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $expense = $this->expenseService->find($id);
        $expense->load('createdBy', 'updatedBy');

        return $this->success(new ExpenseResource($expense), Api::Success->message());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, string $id)
    {
        $expense = $this->expenseService->find($id);

        if ($expense->created_by !== $request->user()->id) {
            abort(403, 'Only the creator of this expense can update it.');
        }

        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        $updatedExpense = $this->expenseService->update($expense, $data);
        $expense->load('createdBy', 'updatedBy');
        
        return $this->success(new ExpenseResource($updatedExpense), Api::Success->message());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $expense = $this->expenseService->find($id);

        if ($expense->created_by !== $request->user()->id) {
            abort(403, 'Only the creator of this expense can delete it.');
        }

        $this->expenseService->delete($expense);

        return $this->success(null, Api::Success->message());
    }

    public function categories()
    {
        return $this->success(
            $this->expenseService->categories()
        );
    }
}
