<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
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
            $created = Task::create($request->validated());
            $resource_data = new TaskResource($created->load('user'));

            return $resource_data;
        } catch (\Exception $error) {
            Log::error('Invoice not created ' . $error->getMessage());

            return [];
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
