<?php

namespace app\core;

class Session {
    protected const FLASH_KEY= 'flash_messages';
    public function __construct(){
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => $flashMessage) {
            
            $flashMeassage['remove']= true;
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
    public function __destruct()
    {
        $flashMeassages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMeassages as $key => $flashMeassage) {

            if ($flashMeassage['remove']) {
                unset($flashMeassages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMeassages;
    }

}