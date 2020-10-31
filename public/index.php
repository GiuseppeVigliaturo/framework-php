<?php

require_once __DIR__."/../vendor/autoload.php";
//dopo aver importato la classe autoload 
// con use evito di ripetere ogni volta il percorso assoluto 
//quando istanzio una classe

use app\controllers\SiteController;
use app\core\Application;
$app = new Application(dirname(__DIR__));

$app->router->get('/', [SiteController::class, 'home']);

$app->router->get('/contact', [SiteController::class, 'contact']);

$app->router->post('/contact',[SiteController::class,'handleContact']);

$app-> run();