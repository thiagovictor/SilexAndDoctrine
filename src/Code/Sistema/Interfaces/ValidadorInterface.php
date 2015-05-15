<?php

namespace Code\Sistema\Interfaces;

interface ValidadorInterface {

    public function isValid($data);

    public function getMessage();
}
