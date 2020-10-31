<?php

namespace app\core;
class Response{

    public function setStatusCode(int $code){

        //restituisce o setta il codice di risposta http
        http_response_code($code);
    }
}