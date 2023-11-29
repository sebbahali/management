<?php

namespace App\Http\Controllers;

use App\Exceptions\Handler;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Observers\TaskObserver;
use App\Services\TaskService;

class TaskController extends Controller
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $users = $this->taskService->getUserList();
        return view('task', compact('users'));
    }
    public function showall()
    {
        return app(Handler::class)->handleTaskException(function () {
            $tasks = Task::with('users')->paginate(10);
            return view('alltask', compact('tasks'));
        });
    }
    public function showalluser()
    {
        return app(Handler::class)->handleTaskException(function () {
            $tasks = auth()->user()->tasks()->paginate(10);
            return view('alltaskuser', compact('tasks'));
        });
    }
    public function store(TaskRequest $request){

        $validatedData = $request->validated();
        
        $task = $this->taskService->createTask($validatedData);
        if ($task) {
            $task->users()->attach($request->input('users'));
            return redirect()->back()->with('success', 'Task created successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to create task');
        }
  
    }

public function done(task $task){

    
    $task->update(['status' => 'completed']);

    return redirect()->back()->with('success', 'Task Marked Done successfully');
}

public function destroy(task $task){

    $task->users()->detach();
    $task->delete();
    return redirect()->back()->with('destroy', 'Task deleted successfully');
}

public function update(task $task){
    $users=User::all();
    return view('updatetask',compact('task','users'));
}
public function edit(TaskRequest $request,task $task){

    $validatedData = $request->validated();

    if ($this->taskService->updateTask($task, $validatedData)) {
        $task->users()->sync($request->input('users'));
        return redirect()->back()->with('success', 'Task updated successfully');
    } else {
        return redirect()->back()->with('error', 'Failed to update task');
    }

}

}
