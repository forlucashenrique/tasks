<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    use HttpResponses;

    public function index()
    {

        return response()->json(
            TaskResource::collection(Task::with('user')->get()),
            200,
            [],
            JSON_UNESCAPED_SLASHES,
        );
    }


    public function store(StoreTaskRequest $request)
    {
        try {
            $isValidFields = $request->validated();

            $created = Task::create($isValidFields);
            $resource_data = new TaskResource($created->load('user'));

            return $this->response('Task Created', 201, $resource_data);
        } catch (ValidationException $error) {

            $errors = $request->errors();
            return $this->error('Task not created', 400, $error->errors());
        }
    }


    public function show(string $id)
    {
        return response()->json(
            new TaskResource(Task::where('id', $id)->first()),
            200,
            [],
            JSON_UNESCAPED_SLASHES,
        );
    }


    public function update(Request $request, string $id) {}


    public function destroy(string $id)
    {
        //
    }
}
