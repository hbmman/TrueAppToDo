<?php
declare(strict_types=1);

namespace App\Entity;


class User
{
    public const STATUS_ADMIN = 1;
    public const STATUS_DEFAULT = 2;
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var
     */
    private $status;

    /**
     * User constructor.
     * @param $login
     * @param $password
     */
    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
        $this->status = self::STATUS_DEFAULT;
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
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function verifyPassword(string $password)
    {
        return $this->password === $password;
    }

    /**
     * @return int
     */
    public function getStatus():int
    {
        return $this->status;
    }
}
