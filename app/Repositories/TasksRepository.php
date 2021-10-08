<?php
namespace App\Repositories;

use App\Models\Collections\TasksCollection;
use App\Models\Task;

interface TasksRepository
{
    public function save(Task $task): void;
    public function getAll(): TasksCollection;
    public function delete(Task $task): void;
    public function getOne(string $id): ?Task;
}