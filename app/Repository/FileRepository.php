<?php


namespace App\Repository;


use App\Accessor\ObjectPropertyAccessor;
use App\Database\Connection;
use App\Entity\File;

class FileRepository
{
    /**
     * @var Connection
     */
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(File $file)
    {
        $stm = $this->connection->prepare(
            "INSERT INTO files SET 
                path = :path, 
                filename = :filename, 
                extension = :extension, 
                size = :size, 
                mime_type = :mimeType
                ");

        $accessor = new ObjectPropertyAccessor($file);

        $stm->execute([
            'path' => $accessor->getPropertyValue('path'),
            'filename' => $accessor->getPropertyValue('filename'),
            'extension' => $accessor->getPropertyValue('extension'),
            'size' => $accessor->getPropertyValue('size'),
            'mimeType' => $accessor->getPropertyValue('mimeType'),
        ]);

        $accessor->setPropertyValue('id', (int)$this->connection->lastInsertId());
    }
}
