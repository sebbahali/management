<?php
namespace App\Services;
use App\Models\Task;
use App\Models\User;

class TaskService
{
    public function getUserList()
    {
        return User::select('id', 'name')->get();
    }
    public function createTask(array $taskData): Task
    {
        $data = [];
        
        if (isset($taskData['image']) && is_array($taskData['image'])) {
            foreach ($taskData['image'] as $file) {
                $destinationPath = 'public/files/';
                $file_name = time() . '_' . uniqid() . "." . $file->getClientOriginalExtension();
                $file->storeAs($destinationPath, $file_name);
                $data[] = $file_name;
            }
        }
        
        $taskData['images'] = json_encode($data);

        $task = Task::create($taskData);
        return $task;
    }
    public function updateTask(Task $task, array $validatedData): void
    {
        $data = [];

        if (isset($validatedData['image']) && is_array($validatedData['image'])) {
            foreach ($validatedData['image'] as $file) {
                $destinationPath = 'public/files/';
                $file_name = time() . '_' . uniqid() . "." . $file->getClientOriginalExtension();
                $file->storeAs($destinationPath, $file_name);
                $data[] = $file_name;
            }
            $validatedData['images'] = json_encode($data);
        }

        $task->update($validatedData);
    }
}