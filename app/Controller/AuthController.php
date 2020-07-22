<?php


namespace App\Controller;


use App\Service\Auth\AuthService;
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

        $dto = new SignupDto();

        $user = $this->authService->signup($dto);

        return json_encode([
            'id' => $user->getId(),
            'login' => $user->getLogin()
        ]);
    }

}
