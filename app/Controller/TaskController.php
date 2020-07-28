<?php


namespace App\Controller;


use App\Service\Tasks\CreateDto;
use App\Service\Tasks\TaskService;

class TaskController
{
    private TaskService $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function create():string
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $dto = new CreateDto();
        $dto->attachment = $data['attachment'];
        $dto->executor = $data['executor'];
        $dto->creator = $data['creator'];
        $dto->content = $data['content'];

        $task = $this->taskService->create($dto);

        return json_encode([
            'id' => $task->getId(),
            'attachment' => $task->getAttachment() == null ? null :[
                $task->getAttachment()->getId(),
                $task->getAttachment()->getPath(),
                $task->getAttachment()-> getMimeType(),
                $task->getAttachment()->getExtension(),
                $task->getAttachment()->getSize(),
                $task->getAttachment()->getFilename()
            ],
            'executor' => [
                $task->getExecutor()->getId(),
                $task->getExecutor()->getLogin(),
                $task->getExecutor()->getStatus()
            ],
            'creator' => [
                $task->getCreator()->getId(),
                $task->getCreator()->getLogin(),
                $task->getCreator()->getStatus()
            ],
            'content' => $task->getContent()
        ]);
    }

}
