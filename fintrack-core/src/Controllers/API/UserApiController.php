<?php
namespace FinTrack\Core\Controllers\API;

use App\Http\Resources\UserResource;
use FinTrack\Core\Models\User;
use FinTrack\Core\Traits\ApiResponse;
use Illuminate\Http\Request;


class UserApiController
{
    use ApiResponse;

    public function index(Request $request)
    {
        $query = User::query();

        if($request->has('id')){
            $query->where('id', $request->input('id'));
        }

        $users = (new UserResource($query->get()))->toArray($request);

        return $this->success($users, 'Users retrieved successfully');
    }
}