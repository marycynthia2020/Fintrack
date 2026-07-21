<?php
namespace Fintrack\Core\Controllers;

use App\Http\Controllers\Controller;
use FinTrack\Core\Traits\ApiResponse;
use Illuminate\Support\Facades\View;



class BaseController extends Controller
{
    use ApiResponse;

    function render(string $view, array $data = [], array $options = [])
    {
        return view($view, $data, $options);
    }
}