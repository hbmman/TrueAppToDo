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

    private $fileRepository;
    public function __construct(string $uploadDir, FileRepository $fileRepository)
    {
        $this->uploadDir = $uploadDir;
        $this->fileRepository = $fileRepository;
    }

    public function upload(UploadFileInfo $info):File
    {
        if ($info->error != UPLOAD_ERR_OK){
            throw  new \DomainException("Upload error");
        }

        $name = urlencode(basename($info->name));
        $ext = pathinfo($info->name, PATHINFO_EXTENSION);
        $uniq = uniqid();
        if(!move_uploaded_file($info->tmp, "$this->uploadDir/". $path = "{$uniq}.$ext")){
            throw new \DomainException("Cannot upload file");
        }

        $file = new File($path, $info->mimeType, $info->size);

        $this->fileRepository->add($file);

        return $file;
    }
}
