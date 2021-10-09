<?php
namespace App\Controllers;

use App\Models\Task;
use App\Repositories\CsvTasksRepository;
use App\Repositories\MysqlTasksRepository;
use App\Repositories\TasksRepository;
use Ramsey\Uuid\Uuid;

class TasksController
{
    private TasksRepository $tasksRepository;

    public function __construct()
    {
        $this->tasksRepository = new MysqlTasksRepository();
    }

    public function index()
    {
        if (isset($_SESSION['username']))
        {
            $tasks = $this->tasksRepository->getAll();

            require_once 'app/Views/tasks/index.template.php';
        }
        else
        {
            header('Location: /');
        }
    }

    public function create()
    {
        if (isset($_SESSION['username']))
        {
            require_once 'app/Views/tasks/create.template.php';
        }
        else
        {
            header('Location: /');
        }
    }

    public function store()
    {
        if (isset($_SESSION['username']))
        {
            $task = new Task(
                Uuid::uuid4(),
                $_POST['title'],
            );

            $this->tasksRepository->save($task);

            header('Location: /tasks');
        }
        else
        {
            header('Location: /');
        }
    }

    public function delete(array $vars)
    {
        if (isset($_SESSION['username']))
        {
            $id = $vars['id'] ?? null;
            if ($id == null) header('Location: /tasks');

            $task = $this->tasksRepository->getOne($id);

            if ($task !== null)
            {
                $this->tasksRepository->delete($task);
            }

            header('Location: /tasks');
        }
        else
        {
            header('Location: /');
        }
    }
}