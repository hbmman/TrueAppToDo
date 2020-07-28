<?php
declare(strict_types=1);

namespace App\Service\Files;


use App\Entity\File;
use App\Repository\FileRepository;

class FilesService
{
    /**
     * @var string
     */
    private string $uploadDir;

    /**
     * @var FileRepository
     */
    private FileRepository $files;
    public function __construct(string $uploadDir, FileRepository $files)
    {
        $this->uploadDir = $uploadDir;
        $this->files = $files;
    }

    public function upload(UploadFileInfo $info):File
    {
        if ($info->error != UPLOAD_ERR_OK){
            throw  new \DomainException("Upload error");
        }

        $name = urlencode(basename($info->name));
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $uniq = uniqid();
        if(!move_uploaded_file($info->tmp, "$this->uploadDir/". $path = "{$uniq}.$ext")){
            throw new \DomainException("Cannot upload file");
        }

        $file = new File($path, $info->mimeType, $info->size);

        $this->files->add($file);

        return $file;
    }

    public function getById(int $id):?File
    {
        return $this->files->get($id);
    }
}
