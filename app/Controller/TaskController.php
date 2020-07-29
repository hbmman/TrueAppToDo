<?php


namespace App\Controller;


use App\Entity\Task;
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
        $dto->attachment = $data['attachment'] ?? null;
        $dto->executor = $data['executor'];
        $dto->creator = $data['creator'];
        $dto->content = $data['content'];

        $task = $this->taskService->create($dto);

        return json_encode([
            'id' => $task->getId(),
            'attachment' => $task->getAttachment() == null ? null :[
                'id'=>$task->getAttachment()->getId(),
                'path'=>$task->getAttachment()->getPath(),
                'mimeType'=>$task->getAttachment()-> getMimeType(),
                'extension'=>$task->getAttachment()->getExtension(),
                'size'=>$task->getAttachment()->getSize(),
                'filename'=>$task->getAttachment()->getFilename()
            ],
            'executor' => [
               'id' => $task->getExecutor()->getId(),
               'login' => $task->getExecutor()->getLogin(),
               'status' => $task->getExecutor()->getStatus()
            ],
            'creator' => [
                'id'=>$task->getCreator()->getId(),
                'login'=>$task->getCreator()->getLogin(),
                'status'=>$task->getCreator()->getStatus()
            ],
            'content' => $task->getContent()
        ]);
    }

    public function index():string
    {
        $limit = $_GET['limit'] ?? 10;
        $offset = $_GET['offset'] ?? 0;

        $tasks = $this->taskService->list((int)$limit, (int)$offset);

        $response = array_map(function ($task){

            /** @var Task $task */

            return [
                'id' => $task->getId(),
                'attachment' => $task->getAttachment() == null ? null :[
                    'id'=>$task->getAttachment()->getId(),
                    'path'=>$task->getAttachment()->getPath(),
                    'mimeType'=>$task->getAttachment()-> getMimeType(),
                    'extension'=>$task->getAttachment()->getExtension(),
                    'size'=>$task->getAttachment()->getSize(),
                    'filename'=>$task->getAttachment()->getFilename()
                ],
                'executor' => [
                    'id' => $task->getExecutor()->getId(),
                    'login' => $task->getExecutor()->getLogin(),
                    'status' => $task->getExecutor()->getStatus()
                ],
                'creator' => [
                    'id'=>$task->getCreator()->getId(),
                    'login'=>$task->getCreator()->getLogin(),
                    'status'=>$task->getCreator()->getStatus()
                ],
                'content' => $task->getContent()
            ];
        }, $tasks);

        return json_encode($response);
    }
}
