<?php


namespace App\Service\Auth;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Storage\SessionStorage;

class AuthService
{

    /**
     * @var UserRepository
     */
    private UserRepository $users;
    /**
     * @var SessionStorage
     */
    private SessionStorage $sessionStorage;

    public function __construct(UserRepository $users, SessionStorage $sessionStorage)
    {
        $this->users = $users;
        $this->sessionStorage = $sessionStorage;
    }

    public function signup(SignupDto $dto):User
    {
        if($userExist = $this->users->findOneByLogin($dto->login)){
            throw  new \DomainException("This login name is already in use ");
        }

        $user = new User($dto->login, $dto->password);
        $this->users->add($user);
        return $user;
    }

    public function login(LoginDto $dto):User
    {
        if(!$user = $this->users->findOneByLogin($dto->login)){
            throw  new \DomainException("User not found. ");
        }

        if(!$user->verifyPassword($dto->password)){
            throw new \DomainException("Wrong password. ");
        }

        $this->sessionStorage->set('user', $user);

        return $user;
    }
}
