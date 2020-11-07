<?php

namespace app\core;
use app\coreModel;

abstract class DbModel extends Model{

    abstract public function tableName(): string;

    abstract public function attributes(): array;

    public function save(){

        $tableName = $this->tableName();
        //users
        $attributes = $this->attributes(); 
        //i nomi dei campi firstname,lastname, ecc...
        $params = array_map(fn($attr)=> ":$attr",$attributes);
        // var_dump($params);
        // die();
        $statement = self::prepare("INSERT INTO $tableName (".implode(',',$attributes).")
                        VALUES(".implode(',', $params).")");

            foreach ($attributes as $attribute) {
                
                $statement->bindValue(":$attribute", $this->{$attribute});
            }
    }

    public static function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);
    }
}