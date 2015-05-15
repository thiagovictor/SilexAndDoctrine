<?php

namespace Code\Sistema\Validator;

use Code\Sistema\Interfaces\ValidadorInterface;

class NumericValidador implements ValidadorInterface{

    private $message;

    public function isValid($data) {
        if (!is_numeric($data)) {
            $this->message = "O valor informado '{$data}' nao e numerico";
            return false;
        }
        $this->message = "";
        return true;
    }

    public function getMessage() {
        return $this->message;
    }

}
