<?php

namespace app\core;
use app\core\Model;

abstract class DbModel extends Model{

    //ritornerà il nome di una tabella specifica a seconda di dove lo chiamo
    abstract public function tableName(): string;

    //ritornerà il nome degli attributi/colonne da salvare nel database
    //non tutti infatti andranno salvati esempio confirmPassword non c'è nel DB
    abstract public function attributes(): array;

    abstract public function primaryKey(): string;
    
    //prendo gli attributi dal Model e li salvo nella tabella corrispondente
    public function save(){

        //users
        $tableName = $this->tableName();
        //recupero solo i nomi delle colonne che sono realmente nel db
        $attributes = $this->attributes(); 
        //i nomi dei campi firstname,lastname, ecc...
        $params = array_map(fn($attr)=> ":$attr",$attributes);
        // var_dump($params);
        // die();
        /**inserisco all'interno delle colonne della tabella  i valori dei campi
         * con il segnaposto per ora
        */
        $statement = self::prepare("INSERT INTO $tableName (".implode(',',$attributes).")
                        VALUES(".implode(',', $params).")");

            foreach ($attributes as $attribute) {

                if ($attribute === "password") {
                $this->{$attribute} = password_hash($this->{$attribute}, PASSWORD_DEFAULT);
                }
                
                $statement->bindValue(":$attribute", $this->{$attribute});
            }

            $statement->execute();
            return true;
    }

    public function findOne($where){//ritorna tutto il record dell'utente 
        //['firstname'=>JANE ,'email'=>tarzan@gmail.com,...]
        /**static si riferisce alla classe in cui ci troviamo */
        $tableName = static::tableName();
        //prendo solo i campi della tabella firstname,lastname,email,ecc..
        $attributes = array_keys($where);

        $sql = implode("AND",array_map(fn($attr)=> "$attr = :$attr",$attributes));

        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");

        foreach ($where as $key => $item) {
            //$key corrisponde alla colonna per esempio firstname,lastname,ecc..
            //$item rappresenta il valore del campo es:Giuseppe per firstname
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        //ritorno tutto l'oggetto riferito all'utente e una istanza della classe passandola come primo parametro a fetchObject
        return $statement->fetchObject(static::class);


    }

    public static function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);
    }
}