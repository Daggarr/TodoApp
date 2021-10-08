<?php
namespace App\Repositories;

use App\Models\Collections\TasksCollection;
use App\Models\Task;
use PDO;
use PDOException;

class MysqlTasksRepository implements TasksRepository
{
    private PDO $conn;

    public function __construct()
    {
        $config = parse_ini_file('config.ini');

        try {
            $this->conn = new PDO("mysql:host={$config['serverName']};dbname={$config['dbName']}", $config['dbUser'], $config['dbPassword']);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function save(Task $task): void
    {
        $sql = "INSERT INTO Tasks (id, title, status, created_at) VALUES (?, ?, ?, ?)";
        $statement = $this->conn->prepare($sql);
        $statement->execute([
            $task->getId(),
            $task->getTitle(),
            $task->getStatus(),
            $task->getCreatedAt()
        ]);
    }

    public function getAll(): TasksCollection
    {
        $collection = new TasksCollection();

        $sql = "SELECT * FROM Tasks";
        $statement = $this->conn->query($sql);
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tasks as $task)
        {
            $collection->add(new Task(
                $task['id'],
                $task['title'],
                $task['status'],
                $task['created_at']
            ));
        }

        return $collection;
    }

    public function delete(Task $task): void
    {
        $sql = "DELETE FROM Tasks WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->execute([$task->getId()]);
    }

    public function getOne(string $id): ?Task
    {
        $sql = "SELECT * FROM Tasks WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->execute([$id]);
        $task = $statement->fetch();

        return new Task(
            $task['id'],
            $task['title'],
            $task['status'],
            $task['created_at']
        );
    }
}