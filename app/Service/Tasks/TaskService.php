<?php
declare(strict_types=1);

namespace App\Service\Tasks;


use App\Entity\Task;
use App\Repository\FileRepository;
use App\Repository\UserRepository;

class TaskService
{
    private UserRepository $users;
    private FileRepository $files;

    public function __construct(UserRepository $users, FileRepository $files)
    {
        $this->users = $users;
        $this->files = $files;
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

        return $task;
    }

}
