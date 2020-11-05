<?php
namespace app\core;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;

    public Database $db;
    public static Application $app;
    public Controller $controller;

    public function __construct($rootPath,array $config)
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
        //istnazio una classe database
        $this->db = new Database($config['db']);
    }

    public function run()
    {
       echo $this->router->resolve();
    }

    public function setController(\app\core\Controller $controller){
        return $this->controller = $controller;
    }

    public function getController(\app\core\Controller $controller)
    {
        return $this->controller = $controller;
    }
}