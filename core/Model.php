<?php
/**Questi metodi sono a disposizione di tutti i Models */
namespace app\core;
//per evitare solo di creare un'istanza di questo modello
abstract class Model{

    public const RULE_REQUIRED= 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';

    public function loadData($data){

        foreach ($data as $key => $value) {
            //verifichiamo se le varie chiavi esistono in questa classe 
            //esempio: firstname che non è presente qui ma lo è in auth controller che estende questa classe
           if (property_exists($this,$key)) {
               /**
                * con questo approccio assegno i dati passati come proprietà del model
                *e creo una variabile con il nome della key che prende il valore corrispondente
                */
               $this->{$key}= $value;
           }
        }
    }


    //questa verrà implementata nella classe figlia
    abstract public function rules():array;

    public array $errors = [];

    public function validate()
    {/** questa funzione rules prende l'array dalla classe Register Model da cui
    *questa classe viene estesa */
        foreach ($this->rules() as $attribute => $rules) {
            //attenzione ad ogni attributo corrisponde una o più regole per questo c'è rules
            $value =$this->{$attribute};
            //value sono i campi del form
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                /**se la regole dice required è il campo è vuoto 
                 * allora aggiungo l'errore */
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addError($attribute, self::RULE_REQUIRED);
                }
                /**se la regola è email valida e il campo 
                 * non contiene email valida allora aggiungo l'errore */
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, self::RULE_EMAIL);
                }

                if ($ruleName === self::RULE_MIN && strlen($value)< $rule['min']) {
                    $this->addError($attribute, self::RULE_MIN,$rule);
                }

                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addError($attribute, self::RULE_MAX,$rule);
                }
                //{$rule['match']} è il nome dell'attributo quindi password mentre $value è la password di conferma
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $this->addError($attribute, self::RULE_MATCH, $rule);
                }
            }
        }

        return empty($this->errors);
    }

    /**per sostituire i placeholder all'interno del messaggio passo un parametro */
    public function addError( string $attribute, string $rule, $params = []){
        $message = $this->errorMessage()[$rule] ?? '';
        /**params è un array per esempio password ha required, min e max
         * key equivale a required, min o max
         * value è il valore corrente
         * {{$key}} serve per cercare il placeholder
         */
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}",$value, $message);
        }
        //aggiungo un elemento all'array con chiave data da attribute cioè il campo del form
        $this->errors[$attribute][]= $message;
    }

    public function errorMessage(){
        return [
    self::RULE_REQUIRED => 'This field is required',
    self::RULE_EMAIL => 'This field must be valid email address',
    self::RULE_MIN => 'Min length is {min}',
    self::RULE_MAX => 'Max length is {max}',
    self::RULE_MATCH => 'This field must be the same as {match}'
        ];
    }
}