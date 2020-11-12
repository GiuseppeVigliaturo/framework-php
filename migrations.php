<?php

use app\core\Application;

require_once __DIR__ . "/vendor/autoload.php";
//dopo aver importato la classe autoload 
// con use evito di ripetere ogni volta il percorso assoluto 
//quando istanzio una classe
/**carico i dati nel file .env */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();



//configurazione del database
$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];
$app = new Application(__DIR__, $config);


$app->db->applyMigrations();

