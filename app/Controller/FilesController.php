<?php


namespace App\Controller;


use App\Service\Files\FilesService;
use App\Service\Files\UploadFileInfo;

class FilesController
{
    /**
     * @var FilesService
     */
    private FilesService $fileService;

    public function __construct(FilesService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function upload():string
    {
        $info = new UploadFileInfo();

        $info->size = $_FILES['file']['size'];
        $info->mimeType = $_FILES['file']['type'];
        $info->name = $_FILES['file']['name'];
        $info->error = $_FILES['file']['error'];
        $info->tmp  = $_FILES['file']['tmp_name'];

        $file = $this->fileService->upload($info);

        return json_encode([
            'id' => $file->getId(),
            'size' => $file->getSize(),
            'mimeType' => $file->getMimeType(),
            'extension' => $file->getExtension(),
            'path' => $file->getPath()
        ]);
    }

    public function display():void
    {

    }
}
