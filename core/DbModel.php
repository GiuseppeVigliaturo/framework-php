<?php

namespace app\core;
use app\core\Model;

abstract class DbModel extends Model{

    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function primaryKey(): string;
    
    public function save(){

        $tableName = $this->tableName();
        //users
        $attributes = $this->attributes(); 
        //i nomi dei campi firstname,lastname, ecc...
        $params = array_map(fn($attr)=> "$attr = :$attr",$attributes);
        // var_dump($params);
        // die();
        $statement = self::prepare("INSERT INTO $tableName (".implode(',',$attributes).")
                        VALUES(".implode(',', $params).")");

            foreach ($attributes as $attribute) {
                
                $statement->bindValue(":$attribute", $this->{$attribute});
            }

            $statement->execute();
            return true;
    }

    public function findOne($where){

        $tableName = $this->tableName();
        $attributes = array_keys($where);

        $sql = implode("AND",array_map(fn($attr)=> "$attr = :$attr",$attributes));

        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");

        foreach ($where as $key => $item) {

            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);


    }

    public static function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);
    }
}