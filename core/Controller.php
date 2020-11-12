<?php

namespace app\core;

class Controller{

    //scelgo il layout dinamicamente
    public string $layout = 'main';
    public function setLayout($layout){
        $this->layout = $layout;
    }

    //passo la pagina e eventuali variabili alla funzione 
    //che reinderizza le views
    public function render($view, $params = []){

        return Application::$app->router->renderView($view, $params);
    }
}