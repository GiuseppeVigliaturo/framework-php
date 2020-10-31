<?php
namespace app\core;

class Application
{

    public Router $router;
    public Request $request;

    public function __construct()
    {
        $this->request = new Request();
        /*
        *istanziando la classe Router nel costruttore 
        *di application quando creiamo una istanza di application abbiamo 
        *direttamente accesso al router
        */
        $this->router = new Router($this->request);
        
    }

    public function run()
    {
        $this->router->resolve();
    }
}