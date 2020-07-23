<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use App\Controller\AuthController;
use App\Service\Auth\AuthService;
use Engine\Router\Router;
use Engine\Container\Container;
use App\Repository\UserRepository;
use App\Database\Connection;
use App\Storage\SessionStorage;


require_once dirname(__DIR__) . "/vendor/autoload.php";

$router = new Router();
$container = new Container();

$router->add('/signup', AuthController::class, "signup");
$router->add('/login', AuthController::class, "login");

$container->set(SessionStorage::class, function (){
    return new SessionStorage();
});

$container->set(Connection::class, function (Container $container){
    return new Connection("mysql:host=localhost:8889;dbname=todo_true", "hbmman", "secret"); //mysql://hbmman:secret@127.0.0.1:8889/true_App
});

/** @var Connection $connection */
$connection = $container->get(Connection::class);
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$container->set(UserRepository::class, function (Container $container){
    return new UserRepository($container->get(Connection::class));
});
$container->set(AuthService::class, function(Container $container){
    return new AuthService(
        $container->get(UserRepository::class),
        $container->get(SessionStorage::class)
    );
});

$container->set(AuthController::class, function (Container $container) {
    return new AuthController($container->get(AuthService::class));
});

$match = $router->match($_SERVER['REQUEST_URI']);

var_dump($match);



$controller = $container->get($match['_controller']);

$response = call_user_func([$controller, $match['_action']]);

echo $response;
