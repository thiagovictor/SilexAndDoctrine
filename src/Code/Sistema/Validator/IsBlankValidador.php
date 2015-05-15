<?php

namespace Code\Sistema\Validator;

use Code\Sistema\Interfaces\ValidadorInterface;

class IsBlankValidador implements ValidadorInterface {

    private $menssage;

    public function isValid($data) {
        if ($data === "" or $data === null) {
            $this->menssage = "Este campo nao pode estar vazio";
            return false;
        }
        $this->menssage = "";
        return true;
    }

    public function getMessage() {
        return $this->menssage;
    }
}
