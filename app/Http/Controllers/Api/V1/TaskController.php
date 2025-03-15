<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Models\Task;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    use HttpResponses;

    public function index()
    {

        try {
            $tasks = TaskResource::collection(Task::with('user')->whereNull('excluded_date')->get());
            return $this->response('success', 200, $tasks);
        } catch (Exception $error) {
            return $this->response('error', 400, [$error]);
        }
    }


    public function store(StoreTaskRequest $request)
    {
        try {
            $validFields = $request->validated();

            $created = Task::create($validFields);
            $resource_data = new TaskResource($created->load('user'));

            return $this->response('success', 201, $resource_data);
        } catch (Exception $error) {
            return response()->json([
                'message' => 'An error occurred while creating the invoice.',
                'error' => $error->getMessage()
            ], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $task = new TaskResource(Task::where('id', $id)->first());
            return $this->response('success', 200, $task);
        } catch (Exception $error) {
            return $this->response('error', 400, [$error]);
        }
    }


    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $validFields = $request->validated();

            $isUpdated = $task->update([
                'title' => $validFields['title'],
                'description' => $validFields['description'],
                'finish_date_limit' => $validFields['finish_date_limit'],
                'finished' => $validFields['finished'],
                'finished_date' => $validFields['finished'] ? Carbon::now() : null,
            ]);

            if ($isUpdated) {
                $resource_data = new TaskResource($task->load('user'));
            }

            return $this->response('success', 200, $resource_data);
        } catch (Exception $error) {
            return response()->json([
                'message' => 'An error occurred while creating the invoice.',
                'error' => $error->getMessage()
            ], 500);
        }
    }


    public function destroy(Task $task)
    {
        try {
            $isDeleted = $task->update([
                'excluded_date' => Carbon::now(),
            ]);

            if ($isDeleted) {
                return $this->response('success', 204);
            }
        } catch (Exception $error) {
            return $this->error('error', 400, [$error]);
        }
    }
}
