<?php
namespace FinTrack\FinLib\Controllers\Web;

use App\Http\Controllers\Controller;
use FinTrack\FinLib\Models\Expense;

class ExpenseController extends Controller
{
    public function index()
    {
        $incomes = Expense::all();
        return view('fin-lib::income.index',['incomes' => $incomes]);
    }

    public function create() 
    {
        return view('income.index');
    }

    public function store()
    {

    }
    public function edit()
    {
        return view('income.edit');
    }

    public function update()
    {

    }

    public function destroy()
    {
        
    }
    
}
