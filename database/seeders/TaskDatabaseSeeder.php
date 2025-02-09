<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
/**
 * Class TaskTableSeeder.
 */
class TaskDatabaseSeeder extends Seeder
{
    /**
     * Run the database seed.
     */
    public function run(): void
    {
        Task::create([
            'title' => trans("seeders.Start your task"),
            'description'=> trans("seeders.description task"),
            'status' => 'pending',
            'due_date' => '2025-02-28 19:09:32'
        ]);
        Task::create([
            'title' => trans("seeders.Start your task2"),
            'description'=> trans("seeders.description task2"),
            'status' => 'pending',
            'due_date' => '2025-02-28 19:09:32'
        ]);

        
    }
}
