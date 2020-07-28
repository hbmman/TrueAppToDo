<?php
declare(strict_types=1);


namespace App\Repository;


use App\Accessor\ObjectPropertyAccessor;
use App\Database\Connection;
use App\Entity\User;

class UserRepository
{
    /** @var Connection  */
    private Connection $connection;
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(User $user):void
    {
        $stm = $this->connection->prepare("INSERT INTO users SET login = :login, password = :password, status = :status");

        $accessor = new ObjectPropertyAccessor($user);

        $stm->execute([
           'login' => $accessor->getPropertyValue('login'),
           'password' => $accessor->getPropertyValue('password'),
           'status' => $accessor->getPropertyValue('status'),
        ]);

        $accessor->setPropertyValue('id', (int)$this->connection->lastInsertId());
    }

    public function findOneByLogin(string $login):?User
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE login = :login");

        $stmt->execute(['login' => $login]);

        if(!$row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            return null;
        }

        /** @var User $user */
        $user = (new \ReflectionClass(User::class))->newInstanceWithoutConstructor();

        $accessor = new ObjectPropertyAccessor($user);

        $accessor->setPropertyValue('id', (int)$row['id']);
        $accessor->setPropertyValue('status', (string)$row['status']);
        $accessor->setPropertyValue('login', (string)$row['login']);
        $accessor->setPropertyValue('password', (string)$row['password']);

        return $user;
    }

    public function get(int $id):?User
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = :id");

        $stmt->execute(['lid' => $id]);

        if(!$row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            throw new \DomainException("User not found");
        }

        /** @var User $user */
        $user = (new \ReflectionClass(User::class))->newInstanceWithoutConstructor();

        $accessor = new ObjectPropertyAccessor($user);

        $accessor->setPropertyValue('id', (int)$row['id']);
        $accessor->setPropertyValue('status', (string)$row['status']);
        $accessor->setPropertyValue('login', (string)$row['login']);
        $accessor->setPropertyValue('password', (string)$row['password']);

        return $user;
    }
}
