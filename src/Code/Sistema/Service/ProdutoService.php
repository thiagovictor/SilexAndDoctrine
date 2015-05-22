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
    
    public function posValidation() {
        $this->object->eraseTags();
    }
    public function ajustaData(array $data = array()) {
        
        if(!empty($data["categoria"])){
            $data["categoria"] = $this->em->getReference("Code\Sistema\Entity\Categoria", $data["categoria"]);
        }
        
        if (empty($data["tags"])) {
            return $data;
        }
        if (strstr($data["tags"], ',')) {
            $IDs = explode(",", $data["tags"]);
            foreach ($IDs as $value) {
                $tags[] = $this->em->getReference("Code\Sistema\Entity\Tag", $value);
            }
            $data["tags"] = $tags;
            return $data;
        }
        
        $data["tag"] = $this->em->getReference("Code\Sistema\Entity\Tag", $data["tags"]);
        unset($data["tags"]);
        return $data;
    }
}
