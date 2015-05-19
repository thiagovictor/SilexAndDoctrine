<?php

namespace Code\Sistema\Service;

use Doctrine\ORM\EntityManager;
use Code\Sistema\Entity\Produto;
use Code\Sistema\Service\AbstractService;

class ProdutoService extends AbstractService {

    public function __construct(EntityManager $em, Produto $produto) {
        parent::__construct($em);
        $this->object = $produto;
        $this->entity = "Code\Sistema\Entity\Produto";
    }
    
    public function ajustaData(array $data = array()) {
        $data["categoria"] = $this->em->getReference("Code\Sistema\Entity\Categoria", $data["categoria"]);
        return $data;
    }

}
