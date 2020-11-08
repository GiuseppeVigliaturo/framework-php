<?php

use app\core\Application;

class m0001_initial {

    public function up(){

        $db = Application::$app->db;
        $SQL= "CREATE TABLE IF NOT EXISTS users ( 
        id int  NOT NULL AUTO_INCREMENT PRIMARY KEY, 
        email varchar(255), 
        firstname varchar(255), 
        lastname varchar(255),
        status int,
        created_at timestamp DEFAULT current_timestamp);";
        $db->pdo->exec($SQL);

    }

    public function down()
    {

        $db = Application::$app->db;
        $SQL = "DROP TABLE users();";
        $db->pdo - exec($SQL);
    }

}