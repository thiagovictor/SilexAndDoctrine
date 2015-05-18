<?php

namespace Code\Sistema\Service;

use Doctrine\ORM\EntityManager;
use Code\Sistema\Entity\Categoria;
use Code\Sistema\Service\AbstractService;

class CategoriaSevice extends AbstractService {

    public function __construct(EntityManager $em, Categoria $categoria) {
        parent::__construct($em);
        $this->object = $categoria;
        $this->entity = "Code\Sistema\Entity\Categoria";
    }

}
