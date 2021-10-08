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
        $tasks = $this->tasksRepository->getAll();

        if (isset($_SESSION['username']))
        {
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
        $task = new Task(
          Uuid::uuid4(),
          $_POST['title'],
        );

        $this->tasksRepository->save($task);

        header('Location: /tasks');
    }

    public function delete(array $vars)
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
}