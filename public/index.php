<?php

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;
use app\models;
require_once __DIR__."/../vendor/autoload.php";
//dopo aver importato la classe autoload 
// con use evito di ripetere ogni volta il percorso assoluto 
//quando istanzio una classe
/**carico i dati nel file .env */
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();



//configurazione del database
$config = [
    'userClass'=> app\models\RegisterModel::class,
    'db'=>[
        'dsn'=> $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];
$app = new Application(dirname(__DIR__),$config);

$app->router->get('/', [SiteController::class, 'home']);

$app->router->get('/contact', [SiteController::class, 'contact']);

$app->router->post('/contact',[SiteController::class,'handleContact']);

$app->router->get('/login',[AuthController::class,'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);

$app-> run();