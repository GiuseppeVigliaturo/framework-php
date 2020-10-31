<?php
namespace app\core;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public static Application $app;

    public function __construct($rootPath)
    {
        self::$ROOT_DIR= $rootPath;

        //creando una proprietà statica e riferendola alla classe 
        //Application accediamo ai metodi senza dover istanziare di volta
        //in volta una nuova classe
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        /*
        *istanziando la classe Router nel costruttore 
        *di application quando creiamo una istanza di application abbiamo 
        *direttamente accesso al router
        */
        $this->router = new Router($this->request,$this->response);
        
    }

    public function run()
    {
       echo $this->router->resolve();
    }
}