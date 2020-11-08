<?php
namespace app\core;

class Application
{

    public $userClass;
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;

    public Session $session;
    public Database $db;
    public static Application $app;
    public Controller $controller;
    public ?DbModel $user;//mettere il punto interrogativo significa che può essere null

    public function __construct($rootPath,array $config)
    {
        /**La classe non dovrebbe essere al di fuori del core 
         * il core deve essere riutilizzabile indipendentemente dalla classe 
         * che usiamo per questo userClass lo abbiamo messo nell'array di configurazione
         */
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR= $rootPath;

        //creando una proprietà statica e riferendola alla classe 
        //Application accediamo ai metodi senza dover istanziare di volta
        //in volta una nuova classe
        self::$app = $this;

        $this->session= new Session();
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

        //una volta recuperata la classe esterna al core dall'array di configurazione
        //chiamo il metodo findOne nel quale specifichiamo che l'id deve corrispondere 
        //al valore recuperato dalla sessione in questo modo posso accedervi da qualunque pagina
        //dell'applicativo
        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        }else{
            //se l'utente non esiste nella sessione lo imposto nullo per evitare errori
            $this->user = null;
        }
        
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

    public function login(DbModel $user){

        /**dobbiamo salvare un identificatore dello user nella sessione
         * quando navighiamo e andiamo in un'altra pagina prendiamo questo identificatore
         * e da questo selezioniamo lo user corrispondente
         */
        $this->user = $user;
        //recupero la chiave primaria dell'oggetto
        $primaryKey = $user->primaryKey();
        //accedo al valore della chiave primaria
        $primaryValue = $user->{$primaryKey};
        //lo inserisco nella sessione
        $this->session->set('user',$primaryValue);
        /**Quando faccio il refresh della pagina lo user non è settato 
         * devo leggere la sessione conoscere il valore della chiave primaria
         * e selezionare l'utente corrispondente 
         * devo farlo nel costruttore
         */
        return true;
    }

    public function logout(){

        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest()
    {
        //se l'utente non esiste allora self::$app->user
        //ritorna false che con il ! diventa true
        //cioè non ha fatto il login
        return !self::$app->user;
    }
}