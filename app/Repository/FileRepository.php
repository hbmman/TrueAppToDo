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

    public function get(int $id):?File
    {
        $stm = $this->connection->prepare(
            "SELECT * FROM files WHERE id =:id"
        );

        $stm->execute(['id' => $id]);

        if(!$row = $stm->fetch(\PDO::FETCH_ASSOC)){
            throw new \DomainException("File not found");
        }

        /** @var File $file */
        $file = (new \ReflectionClass(File::class))->newInstanceWithoutConstructor();

        $accessor = new ObjectPropertyAccessor($file);

        $accessor->setPropertyValue('id', (int)$row['id']);
        $accessor->setPropertyValue('path', (string)$row['path']);
        $accessor->setPropertyValue('filename', (string)$row['filename']);
        $accessor->setPropertyValue('size', (int)$row['size']);
        $accessor->setPropertyValue('mimeType', (string)$row['mime_type']);
        $accessor->setPropertyValue('extension', (string)$row['extension']);

        return $file;
    }
}
