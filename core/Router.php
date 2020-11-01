<?php

namespace app\core;
class Router{

   
    public Request $request;
    public Response $response;
    protected array $routes = [];
    public function __construct(Request $request,Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
    //quando abbiamo una determinata rotta chiamo la callback corispondente
    public function get($path,$callback){
        $this->routes['get'][$path]= $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            $this->response->setStatusCode(404);
            return $this->renderView("_404");
            
        }
        
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        /*se callback è un array prendo il primo
        elemento che corrsponde al controller e ne creo 
        una istanza
        */
        if (is_array($callback)) {
            $instance = new $callback[0]();
            $callback[0]=$instance;
            //in questo modo il primo elemento è ora un oggetto
        }
        return call_user_func($callback);
    }


    public function renderView($view,$params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
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

    protected function renderOnlyView($view, $params = []) {

        foreach ($params as $key => $value) {

            /**
             * piccolo trick se la key si chiama value 
             * mettendo il doppio dollaro $key verrà rinominata come 
             * una variabile di nome $name
             */
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR ."/views/$view.php";
        return ob_get_clean();
    }


}