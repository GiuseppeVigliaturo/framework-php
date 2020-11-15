<?php

namespace app\core;
use app\core\middlewares\BaseMiddleware;

class Controller{

    public array $middlewares = [];
    
    public string $layout = 'main';
    public string $action = '';
    //scelgo il layout dinamicamente
    public function setLayout($layout){
        $this->layout = $layout;
    }

    //passo la pagina e eventuali variabili alla funzione 
    //che reinderizza le views
    public function render($view, $params = []){

        return Application::$app->router->renderView($view, $params);
    }

    public function registerMiddleware(BaseMiddleware $middleware){

        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array{

        return $this->middlewares;
    }

}