<?php

namespace App\Http\Controllers\Task;


use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Http\Requests\Task\TaskCreateRequest;
use App\Services\Task\Interfaces\TaskInterface as TaskService;

class TaskController extends Controller
{
    use ApiResponser;

    public function get(TaskService $task, Request $request)
    {
        return $this->success($request->task());
    }

    public function update($id, TaskService $task, TaskUpdateRequest $request)
    {
        return $this->success($task->save($request, $id), __('task.successfully_updated'));
    }

    public function create(TaskService $task, TaskCreateRequest $request)
    {
        return $this->success($task->create($request));
    }

    public function list(TaskService $task, ListRequest $request, $skip = 0, $take = 10)
    {
        return $this->success($task->list($skip, $take, $request));
    }

    public function delete($id, TaskService $task)
    {
        return $this->success($task->delete($id));
    }

    public function getById($id, TaskService $task)
    {
        return $this->success($task->getById($id));
    }

    public function getByUser(TaskService $user, Request $request)
    {
        return $this->success($user->getByUser($request));
    }
}
