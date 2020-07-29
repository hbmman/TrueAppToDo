<?php
declare(strict_types=1);

namespace App\Service\Tasks;


use App\Entity\Task;
use App\Repository\FileRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;

class TaskService
{
    private UserRepository $users;
    private FileRepository $files;
    private TaskRepository $tasks;

    public function __construct(UserRepository $users, FileRepository $files, TaskRepository $tasks)
    {
        $this->users = $users;
        $this->files = $files;
        $this->tasks = $tasks;
    }

    public function create(CreateDto $dto):Task
    {
        $creator = $this->users->get($dto->creator);
        $executor = $this->users->get($dto->executor);

        $task = new Task($dto->content, $creator, $executor);
        if(!is_null($dto->attachment)){
            $attachment = $this->files->get((int)$dto->attachment);
            $task->setAttachment($attachment);
        }

        $this->tasks->add($task);

        return $task;
    }

    public function list(int $limit, int $offset):array
    {
        return $this->tasks->findAll($limit, $offset);
    }

}
