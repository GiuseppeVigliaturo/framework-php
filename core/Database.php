<?php

namespace app\core;

use \PDO;

class Database{

    public PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? ''; 
        $this->pdo = new \PDO($dsn, $user, $password);
        /**Se c'è qualche problema di connessione o un problema con questa istanza di pdo
         * allora sollevo un'eccezione
         */
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations(){
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];

        /**la funzione scandir List files and directories inside the images directory: */
        $files = scandir(Application::$ROOT_DIR.'/migrations');
        /**la funzione array_diff Compare the values of two arrays, and return the differences: */
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration) {

            if ($migration === '.' || $migration === '..') {
                continue;
            }
            //per poter creare una istanza importo il file della migration
            require_once Application::$ROOT_DIR.'/migrations'.'/'.$migration;
            $className = pathinfo($migration,PATHINFO_FILENAME);

            //creo una nuova istanza della migrazione
            $instance = new $className();
             $this->log("Applying migration $migration");
             //accedo al metodo up e creo la tabella
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        }
        else{
            $this->log("All migrations are applied");
        }
    }

    //creo la tabella delle migrations
    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id int  NOT NULL AUTO_INCREMENT PRIMARY KEY,
            migration varchar(255),
            created_at timestamp DEFAULT current_timestamp
            );"
        );
    }

    //recupero tutte le migrations create
    public function getAppliedMigrations(){

       $statement= $this->pdo->prepare("SELECT migration from migrations");
       $statement->execute();

    return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations){
        /**restituisco i nomi delle migrations tra ('nome migration') */
        $str = implode(",",array_map(fn($m)=>"('$m')", $migrations));
        /**Inserisco i dati nella tabella migrations nella colonna migration */
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
            $str
        ");
        $statement->execute();
    }

    public function prepare($sql){
        return $this->pdo->prepare($sql);
    }

    protected function log($message){

        echo '['.date('Y-m-d H:i:s').'] -'.$message .PHP_EOL;
    }
}