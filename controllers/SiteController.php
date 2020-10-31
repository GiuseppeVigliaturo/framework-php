<?php
namespace app\controllers;

use app\core\Application;

class SiteController{

    //il motivo per cui ritorniamo una view e che possiamo passargli un parametro
    public function home()
    {
        $params = [
            "name" => "Giuseppe Vigliaturo"
        ];
        return Application::$app->router->renderView('home', $params);
    }
  
  
    public function contact()
    {
        return Application::$app->router->renderView('contact');
    }

    public function handleContact(){
        return "Handle submitted Data";
    }
}