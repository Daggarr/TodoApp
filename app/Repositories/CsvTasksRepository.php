<?php

namespace App\Repositories;

use App\Models\Collections\TasksCollection;
use App\Models\Task;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\Writer;

class CsvTasksRepository implements TasksRepository
{
    private Reader $reader;

    public function __construct()
    {
        $this->reader = Reader::createFromPath('storage/tasks.csv','r');
    }

    public function save(Task $task): void
    {
        $writer = Writer::createFromPath('storage/tasks.csv', 'a');
        $writer->insertOne($task->toArray());
    }

    public function getAll(): TasksCollection
    {
        $collection = new TasksCollection();

        foreach ($this->reader->getRecords() as $record)
        {
            $collection->add(new Task(
                $record[0],
                $record[1],
                $record[2],
                $record[3],
            ));
        }

        return $collection;
    }

    public function delete(Task $task): void
    {
        $tasks = $this->getAll();
        $tasks->remove($task);

        $records = [];

        foreach ($tasks->getTasks() as $task)
        {
            $records[] = $task->toArray();
        }

        $writer = Writer::createFromPath('storage/tasks.csv', 'w');
        $writer->insertAll($records);
    }

    public function getOne(string $id): ?Task
    {
        $statement = Statement::create()
            ->where(function ($record) use ($id){
                return $record[0] === $id;
            })
            ->limit(1);

        $record = $statement->process($this->reader)->fetchOne();

        if (empty($record)) return null;

        return new Task(
            $record[0],
            $record[1],
            $record[2],
            $record[3]
        );
    }
}