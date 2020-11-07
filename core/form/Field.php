<?php

namespace app\core\form;
use app\core\Model;

class Field{
    
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';
    

    public string $type;
    public Model $model;
    public string $attribute;

    public function __construct(\app\core\Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        $this->model = $model;
        $this->attribute = $attribute;
    }
    

    //quando proviamo a stampare un oggetto, qualsiasi cosa viene ritornata
    //viene convertita in stringa e stampata
    public function __toString(){

        return sprintf(
            '
            <div class="col">
                <div class="form-group">
                    <label>%s</label>
                    <input name="%s" type="%s" value="%s" class="form-control%s">
                    <div class="invalid-feedback">
                        %s
                    </div>
                </div>  
            </div> 
        ', 
            $this->model->getLabel($this->attribute),
            $this->attribute,
            $this->type,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->model->getFirstError($this->attribute)
    
    );
    }

    public function passwordField(){
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }
}