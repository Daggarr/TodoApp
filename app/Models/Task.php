<?php
namespace App\Models;

use Carbon\Carbon;

class Task
{
    private string $id;
    private string $title;
    private ?string $status;
    private string $createdAt;

    public const STATUS_CREATED = 'created';
    public const STATUS_COMPLETED = 'completed';

    private const STATUSES = [
        self::STATUS_CREATED,
        self::STATUS_COMPLETED
    ];

    public function __construct(
        string $id,
        string $title,
        ?string $status = null,
        ?string $createdAt = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->setStatus($status ?? Task::STATUS_CREATED);
        $this->createdAt = $createdAt ?? Carbon::now();
    }

    public function toArray(): array
    {
        return [
            'id'=>$this->getId(),
            'title'=>$this->getTitle(),
            'status'=>$this->getStatus(),
            'created_at'=>$this->getCreatedAt()
        ];
    }

    public function setStatus(string $status): void
    {
        if (! in_array($status, self::STATUSES))
        {
            return;
        }

        $this->status = $status;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}