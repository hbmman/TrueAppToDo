<?php
declare(strict_types=1);

namespace App\Acl;


use App\Storage\SessionStorage;

class Acl
{
    public const GUEST = 1;
    /**
     * @var SessionStorage
     */
    private SessionStorage $storage;

    /**
     * @var array
     */
    private $rules = [];

    public function __construct(SessionStorage $storage)
    {
        $this->storage = $storage;
    }



    /**
     * @param string $context
     * @return bool
     */
    public function isAllow(string $context):bool
    {
        switch ($context){
            case "/signup":
                return !$this->storage->has('user');
                break;
            case "/login":
                return !$this->storage->has('user');
                break;
            case "/task/create":
                return $this->storage->has('user');
                break;
            default:
                return true;
        }
    }

}
