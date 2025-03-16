<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{

    use HttpResponses;

    public function store(StoreUserRequest $request)
    {
        try {
            $validFields = $request->validated();
            $created = User::create($validFields);
            $resource_data = new UserResource($created);

            return $this->response('success', 201, $resource_data);
        } catch (Exception $error) {
            return $this->error('error', 400, [$error]);
        }
    }
}
