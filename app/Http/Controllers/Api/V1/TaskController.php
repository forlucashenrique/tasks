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
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {

        $user = Auth::user();

        $status_arr = [
            'pending' => 0,
            'done' => 1,
        ];

        try {

            $status = $request->query('status');
            $tasksTemp = Task::where('user_id', $user->id)->whereNull('excluded_date');

            if (!is_null($status)) {
                $tasksTemp->where('finished', $status_arr[$status]);
            }

            $tasks = TaskResource::collection($tasksTemp->get());
            return $this->response('success', 200, $tasks);
        } catch (Exception $error) {
            return $this->response('error', 400, [$error]);
        }
    }


    public function store(StoreTaskRequest $request)
    {
        try {

            $user = Auth::user();

            $validFields = $request->validated();

            $fields = [
                'user_id' => $user->id,
                'title' => $validFields['title'],
                'description' => $validFields['description'],
                'finish_date_limit' => $validFields['finish_date_limit'],
            ];

            $created = Task::create($fields);
            $resource_data = new TaskResource($created->load('user'));

            return $this->response('success', 201, $resource_data);
        } catch (Exception $error) {
            return response()->json([
                'message' => 'An error occurred while creating the task.',
                'error' => $error->getMessage()
            ], 500);
        }
    }



    public function show(string $id)
    {
        try {

            $user = Auth::user();
            $task = Task::findOrFail($id);

            if ($task->user_id !== $user->id) {
                return $this->response('You do not have permission', 403, []);
            }

            $resource_data =  new TaskResource($task);

            return $this->response('success', 200, $resource_data);
        } catch (Exception $error) {
            return $this->response('error', 400, [$error]);
        }
    }


    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {

            $user = Auth::user();

            if ($task->user_id !== $user->id) {
                return $this->response('You do not have permission', 403, []);
            }

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
                'message' => 'An error occurred while creating the task.',
                'error' => $error->getMessage()
            ], 500);
        }
    }


    public function destroy(Task $task)
    {
        try {

            $user = Auth::user();

            if ($task->user_id !== $user->id) {
                return $this->response('You do not have permission', 403, []);
            }

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
