<?php

namespace app\core;
use app\core\exception\NotFoundException;
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
        //prendo l'indirizzo dall'url
        $path = $this->request->getPath();
        //vedo se il metodo è get o post
        $method = $this->request->getMethod();
        //catturo il nome della funzione di callback da invocare
        $callback = $this->routes[$method][$path] ?? false;
        //var_dump($callback);
        //se la funzione non esiste setto lo stato di errore 404
        if ($callback === false) {
            
            throw new NotFoundException();
            
        }
        //se esiste ed è una singola parola allora vengo reindirizzato alla view corrispondente
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        /*se callback è un array prendo il primo
        elemento che corrisponde al controller e ne creo 
        una istanza
        */
        if (is_array($callback)) {
            
            //esempio:
            //prendo solo Sitecontroller::class
            //$app->router->get('/', [SiteController::class, 'home']);
            
            $controller = new $callback[0]();
            //Middleware: quando creiamo una istanza del controller possiamo 
            //anche indicare qual è l'azione per quel controller
            Application::$app->controller = $controller;
            $controller->action =  $callback[1];
            //posso ora accedere al controller
            $callback[0] = $controller;
            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
            
            
        }
        //nel caso del controller creo una istanza del SiteController
        //e gli passo la request inizializzata nel costruttore del router
        // con la request che gli passo abbiamo i dati che inviamo
        return call_user_func($callback, $this->request,$this->response);
    }


    public function renderView($view,$params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
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
        //apre un buffer e comicia a inserirci i dati che cattura
        ob_start();
        include_once Application::$ROOT_DIR ."/views/$view.php";
        //ritorna quello che c'è nel buffer e lo pulisce
        return ob_get_clean();
    }

    protected function layoutContent()
    {
        $layout = Application::$app->layout;
        if (Application::$app->controller->layout) {
            $layout = Application::$app->controller->layout;
        }
        
        //comincio a catturare l'output nel buffer
        ob_start();
        //catturo il layout
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        //ritorno ciò che ho catturato pulendo il buffer
        return ob_get_clean();
    }


}