<?php
declare(strict_types=1);


namespace App\Entity;


class File
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var int
     */
    private $size;

    public function __construct(string $path, string $mimeType, int $size)
    {
        $this->path = $path;
        $this->mimeType = $mimeType;
        $this->size = $size;
        $this->extension = pathinfo($path, PATHINFO_EXTENSION);
        $this->filename = pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }


}
