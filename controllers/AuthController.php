<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\RegisterModel;
use app\models\LoginForm;
class AuthController extends Controller {

    public function login(Request $request,Response $response){
        
        $loginForm = new loginForm();
        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                
                $response->redirect('/');
                return;
            }
        }
        $this->setLayout('auth');
        return $this->render('login',[
            'model'=> $loginForm
        ]);
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
            

            /**se la validazione è andata a buon e ho registrato 
             * correttamente i dati visualizzo un messaggio */
            if ($registerModel->validate() && $registerModel->save()) {
               Application::$app->session->setFlash('success','thanks for registering');
               Application::$app->response->redirect('/');
               exit;
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

    public function logout(Request $request, Response $response){

        Application::$app->logout();
        $response->redirect('/');
    }
}