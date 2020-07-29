<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use App\Acl\Acl;
use App\Controller\AuthController;
use App\Controller\TaskController;
use App\Repository\TaskRepository;
use App\Service\Auth\AuthService;
use App\Service\Tasks\TaskService;
use Engine\Router\Router;
use Engine\Container\Container;
use App\Repository\UserRepository;
use App\Database\Connection;
use App\Storage\SessionStorage;
use App\Controller\FilesController;
use App\Service\Files\FilesService;
use App\Repository\FileRepository;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$router = new Router();
$container = new Container();

$router->add('/signup', AuthController::class, "signup");
$router->add('/login', AuthController::class, "login");
$router->add('/files/upload', FilesController::class, "upload");
$router->add('/files/display', FilesController::class, "display");
$router->add('/tasks/create', TaskController::class, "create");
$router->add('/tasks', TaskController::class, "index");


$container->set('upload.dir', function (){
    return dirname(__DIR__) . "/uploads";
});
$container->set(SessionStorage::class, function (){
    return new SessionStorage();
});
$container->set(Connection::class, function (Container $container){
    return new Connection("mysql:host=localhost:8889;dbname=todo_true", "hbmman", "secret"); //mysql://hbmman:secret@127.0.0.1:8889/true_App
});

/** @var Connection $connection */
$connection = $container->get(Connection::class);
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$container->set(Acl::class, function (Container $container){
    return new Acl(
        $container->get(SessionStorage::class));
});


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


$container->set(FileRepository::class, function (Container $container){
    return new FileRepository($container->get(Connection::class));
});
$container->set(FilesService::class, function (Container $container){
    return new FilesService(
        $container->get('upload.dir'),
        $container->get(FileRepository::class)
    );
});
$container->set(FilesController::class, function (Container $container){
    return new FilesController(
        $container->get(FilesService::class));
});

$container->set(TaskRepository::class, function (Container $container){
    return new TaskRepository($container->get(Connection::class));
});

$container->set(TaskService::class, function (Container $container){
    return new TaskService(
        $container->get(UserRepository::class),
        $container->get(FileRepository::class),
        $container->get(TaskRepository::class)
    );
});

$container->set(TaskController::class, function (Container $container){
    return new TaskController(
        $container->get(TaskService::class)
    );
});


$match = $router->match(preg_replace("#\?.*#", null, $_SERVER['REQUEST_URI']));

//var_dump($match);

/** @var Acl $acl */
$acl = $container->get(Acl::class);

var_dump($_SESSION);

if(!$acl->isAllow($match['_route'])){
    throw new DomainException("Not access");
}

$controller = $container->get($match['_controller']);

$response = call_user_func([$controller, $match['_action']]);

echo $response;
