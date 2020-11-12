<?php

namespace app\models;

use app\core\Application;
use app\core\Model;
use app\models\RegisterModel;

class LoginForm extends Model{

    public string $email = '';
    public string $password = '';
    public function rules(): array
    {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED]
        ];
    }

    public function labels():array {

        return [
            'email' => 'Your Email',
            'password'=>'Password'
        ];
    }

    public function login(){

        //verifico tramite l'email se lo user con cui mi sto loggando esiste nel db
        $user = RegisterModel::findOne(['email'=> $this->email]);
        // var_dump($user->password);
        // die();

        if (!$user) {
           $this->addErrorLogin('email','User does not exist with this email');
           return false;
        }
         if (!password_verify($this->password, $user->password)) {
            $this->addErrorLogin('password', 'Password is incorrect');
            return false;
         }
        return Application::$app->login($user);
    }
}