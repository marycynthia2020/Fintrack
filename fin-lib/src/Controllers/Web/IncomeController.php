<?php
namespace FinTrack\FinLib\Controllers\Web;

use App\Http\Controllers\Controller;
use FinTrack\FinLib\Models\Income;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::all();
        return view('fin-lib::income.index',['incomes' => $incomes]);
    }
}
