<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::pluck('id')->toArray();
        $imagePaths = [
            "1700778971_655fd3db6a7d6.jpg",
            "1700778971_655fd3db6f8fb.jpg",
            "1700778971_655fd3db70f3b.jpg",
        ];

        for ($i = 0; $i < 1000; $i++) {
            $task =  Task::create([
                'title' => 'Test Task ' . ($i + 1),
                'description' => 'This is a test task.',
                'priority' => ['low', 'medium', 'high'][array_rand(['low', 'medium', 'high'])],
                'due_date' => now()->addDays(rand(1, 30)),
                'status' => ['pending', 'in_progress'][array_rand(['pending', 'in_progress'])],
            ]);
            $task->users()->attach($users[array_rand($users)]);
            $task->update(['images' => json_encode($imagePaths)]);

        }
    }
    
}
