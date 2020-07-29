<?php
declare(strict_types=1);


namespace App\Repository;


use App\Accessor\ObjectPropertyAccessor;
use App\Database\Connection;
use App\Entity\File;
use App\Entity\Task;
use App\Entity\User;

class TaskRepository
{
    /**
     * @var Connection
     */
    private Connection $connection;
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(Task $task):void
    {
        $stm = $this->connection->prepare("INSERT INTO tasks SET content = :content, executor_id = :executor_id,
            creator_id = :creator_id, status =:status, attachment_id =:attachment_id ");

        $accessor = new ObjectPropertyAccessor($task);

        $stm->execute([
            'content' => $accessor->getPropertyValue('content'),
            'executor_id' => $accessor->getPropertyValue('executor')->getId(),
            'creator_id' => $accessor->getPropertyValue('creator')->getId(),
            'status' => $accessor->getPropertyValue('status'),
            'attachment_id' => $accessor->getPropertyValue('attachment') == null ? null : $accessor->getPropertyValue('attachment')->getId()
        ]);

        $accessor->setPropertyValue('id', (int)$this->connection->lastInsertId());
    }

    public function findAll(int $limit, int $offset):array
    {
        $stm = $this->connection->prepare(
            "SELECT
                        t.id as t_id, t.content as t_content,
                        t.status as t_status,
                        c.id as c_id, c.login as c_login,
                        c.password as c_password, c.status as c_status,
                        e.id as e_id, e.login as e_login,
                        e.password as e_password, e.status as e_status,
                        a.id as a_id, a.filename as a_filename,
                        a.size as a_size, a.path as a_path,
                        a.extension as a_extension, a.mime_type as a_mime_type
                        from tasks t
                        left join users c on t.creator_id = c.id
                        left join files a on t.attachment_id = a.id
                        left join users e on t.executor_id = e.id
                        LIMIT :limit OFFSET :offset"
        );

        $stm->bindValue("limit", $limit, \PDO::PARAM_INT);
        $stm->bindValue("offset", $offset, \PDO::PARAM_INT);

        $stm->execute();

        $rows = $stm->fetchAll(\PDO::FETCH_ASSOC);

        $tasks = [];

        foreach ($rows as $row){

            /** @var Task $task */
            $task = (new \ReflectionClass(Task::class))->newInstanceWithoutConstructor();
            /** @var User $creator */
            $creator = (new \ReflectionClass(User::class))->newInstanceWithoutConstructor();
            /** @var User $executor */
            $executor = (new \ReflectionClass(User::class))->newInstanceWithoutConstructor();


            $accessorTask = new ObjectPropertyAccessor($task);
            $accessorCreator = new ObjectPropertyAccessor($creator);
            $accessorExecutor = new ObjectPropertyAccessor($executor);


            $accessorTask->setPropertyValue('id', (int)$row['t_id']);
            $accessorTask->setPropertyValue('content', (string)$row['t_content']);
            $accessorTask->setPropertyValue('status', (string)$row['t_status']);


            $accessorCreator->setPropertyValue('id', (int)$row['c_id']);
            $accessorCreator->setPropertyValue('status', (int)$row['c_status']);
            $accessorCreator->setPropertyValue('login', (string)$row['c_login']);
            $accessorCreator->setPropertyValue('password', (string)$row['c_password']);


            $accessorExecutor->setPropertyValue('id', (int)$row['e_id']);
            $accessorExecutor->setPropertyValue('status', (int)$row['e_status']);
            $accessorExecutor->setPropertyValue('login', (string)$row['e_login']);
            $accessorExecutor->setPropertyValue('password', (string)$row['e_password']);


            $accessorTask->setPropertyValue('creator', $creator);
            $accessorTask->setPropertyValue('executor', $executor);


            if(!is_null($row['a_id'])){
                /** @var File $attachment */
                $attachment = (new \ReflectionClass(File::class))->newInstanceWithoutConstructor();

                $accessorAttachment = new ObjectPropertyAccessor($attachment);

                $accessorAttachment->setPropertyValue('id', (int)$row['a_id']);
                $accessorAttachment->setPropertyValue('path', (string)$row['a_path']);
                $accessorAttachment->setPropertyValue('filename', (string)$row['a_filename']);
                $accessorAttachment->setPropertyValue('size', (int)$row['a_size']);
                $accessorAttachment->setPropertyValue('mimeType', (string)$row['a_mime_type']);
                $accessorAttachment->setPropertyValue('extension', (string)$row['a_extension']);

                $accessorTask->setPropertyValue('attachment', $attachment);
            }

            $tasks[] = $task;
        }

        return $tasks;
    }
}
