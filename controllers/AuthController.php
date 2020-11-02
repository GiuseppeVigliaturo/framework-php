<?php
namespace app\controllers;
use app\core\Controller;
use app\core\Request;
use app\models\RegisterModel;
class AuthController extends Controller {

    public function login(){
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function register(Request $request)
    {
        $registerModel = new RegisterModel();
        if ($request->isPost()) {
            /**
             * per validare i dati provenienti dal form faccio fare il lavoro 
             * al Registermodel 
             */
             //carico i dati inviati dal form nel Model
            $registerModel -> loadData($request->getBody());

            /**se la validazione è andata a buon fine allora registro i dati */
            if ($registerModel->validate() && $registerModel->register()) {
                return "success";
            }
            /**inviamo alla view register l'array contentenente i dati registrati e validati */
            return $this->render('register',[
                'model'=> $registerModel
            ]);
        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $registerModel
        ]);
    }
}