<?php

namespace app\core;
class Router{

    public Request $request;
    protected array $routes = [];
    public function __construct(\app\core\Request $request)
    {
        $this->request = $request;
    }
    
    //quando abbiamo una determinata rotta chiamo la callback corispondente
    public function get($path,$callback){
        $this->routes['get'][$path]= $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            return "Not found";
            
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        return call_user_func($callback);
    }


    public function renderView($view)
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent(){
        
        //comincio a catturare l'output nel buffer
        ob_start();
        //catturo il layout
        include_once Application::$ROOT_DIR."/views/layouts/main.php";
        //ritorno ciò che ho catturato pulendo il buffer
        return ob_get_clean();
    }

    protected function renderOnlyView($view) {

        ob_start();
        include_once Application::$ROOT_DIR ."/views/$view.php";
        return ob_get_clean();
    }


}