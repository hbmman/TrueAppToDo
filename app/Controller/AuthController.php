<?php


namespace App\Controller;


use App\Service\Auth\AuthService;
use App\Service\Auth\LoginDto;
use App\Service\Auth\SignupDto;

class AuthController
{
    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function signup():string
    {
        $data = json_decode(file_get_contents("php://input"), true);

        var_dump($data);

        $dto = new SignupDto();
        $dto->password = $data['password'];
        $dto->login = $data['login'];

        $user = $this->authService->signup($dto);

        return json_encode([
            'id' => $user->getId(),
            'login' => $user->getLogin()
        ]);
    }

    public function login():string
    {
        $data = json_decode(file_get_contents("php://input"), true);

        var_dump($data);

        $dto = new LoginDto();
        $dto->password = $data['password'];
        $dto->login = $data['login'];

        $user = $this->authService->login($dto);

        return json_encode([
            'id' => $user->getId(),
            'login' => $user->getLogin()
        ]);
    }
}
