<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
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

    public function handleContact(Request $request){
        $body = $request->getBody();
        return "Handling data";
    }
}