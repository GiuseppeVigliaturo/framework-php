<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
class SiteController extends Controller{

    //il motivo per cui ritorniamo una view e che possiamo passargli un parametro
    public function home()
    {
        $params = [
            "name" => "Giuseppe Vigliaturo"
        ];
        return $this->render('home', $params);
    }
  
  
    public function contact()
    {
        return Application::$app->router->renderView('contact');
    }

    public function handleContact(){
        return "Handle submitted Data";
    }
}