<?php

namespace app\core;

class Session {
    protected const FLASH_KEY= 'flash_messages';
    public function __construct(){
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as &$flashMessage) {
            //appena creo una sessione e aggiungo un elemento in essa setto
            //il valore della chiave remove da false a true in questo modo posso 
            //eliminare il messaggio nel destruct una volta che la sessione termina
            $flashMessage['remove']= true;
        }
        $_SESSION[self::FLASH_KEY]= $flashMessages;
    }
   

    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key]= [
           'remove'=> false,
            'value' => $message
        ];
        
    }

    public function getFlash($key){
      return  $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }


    public function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public function get($key){
       return $_SESSION[$key] ?? false;
    }

    public function remove($key){

        unset($_SESSION[$key]);
    }


    public function __destruct()
    {
        /**rimuovo tutti i messaggi salvati nella sessione al termine della request */
        $flashMeassages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMeassages as $key => $flashMeassage) {

            if ($flashMeassage['remove']) {
                unset($flashMeassages[$key]);
            }
        }
        /** */
        $_SESSION[self::FLASH_KEY] = $flashMeassages;
    }

}
