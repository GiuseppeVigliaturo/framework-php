<?php

use app\core\Application;

class m0002_add_password
{

    public function up()
    {

        $db = \app\core\Application::$app->db;
        $SQL = "ALTER TABLE ADD_COLUMN password varchar(255) NOT NULL;";
        $db->pdo->exec($SQL);
    }

    public function down()
    {

        $db = Application::$app->db;
        $SQL = "ALTER TABLE DROP_COLUMN password;";
        $db->pdo - exec($SQL);
    }
}
