<?php
declare(strict_types=1);

namespace App\Entity;


/**
 * Class Task
 * @package App\Entity
 */
class Task
{
    public const STATUS_PENDING = 1;
    public const STATUS_COMPLETED = 2;
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var User
     */
    private $creator;

    /**
     * @var User
     */
    private $executor;

    /**
     * @var int
     */
    private $status;

    /**
     * @var File
     */
    private $attachment;

    public function __construct(string $content, User $creator, User $executor)
    {
        $this->content = $content;
        $this->creator = $creator;
        $this->executor = $executor;
        $this->status = self::STATUS_COMPLETED;
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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return User
     */
    public function getCreator(): User
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     */
    public function setCreator(User $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return User
     */
    public function getExecutor(): User
    {
        return $this->executor;
    }

    /**
     * @param User $executor
     */
    public function setExecutor(User $executor): void
    {
        $this->executor = $executor;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return File
     */
    public function getAttachment(): File
    {
        return $this->attachment;
    }

    /**
     * @param File $attachment
     */
    public function setAttachment(File $attachment): void
    {
        $this->attachment = $attachment;
    }


}
