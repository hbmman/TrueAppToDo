<?php


namespace App\Service\Auth;


use App\Entity\User;

class AuthService
{
    public function signup(SignupDto $dto):User
    {
        $user = new User($dto->login, $dto->password);

        return $user;
    }
}
