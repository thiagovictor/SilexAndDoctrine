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

    public static function uploadImage(array $files = array()) {
        $types = ["bmp", "jpeg", "png"]; //Extensoes validas
        if (empty($files["tmp_name"])) {
            return false;
        }
        foreach ($types as $type) {
            if (strstr($files["type"], $type)) {
                $imagemPath = "/imgs/produtos/produto_" . time() . "." . $type;
                $completePath = __DIR__ . "/../../../../public";
                if (move_uploaded_file($files["tmp_name"], $completePath . $imagemPath)) {
                    return $imagemPath;
                }
                return false;
            }
        }
        return false;
    }

    publiC static function removeImage($path) {
        $completePath = __DIR__ . "/../../../../public";
        if (unlink($completePath . $path)) {
            return true;
        }
        return false;
    }

    public function ajustaData(array $data = array()) {

        if (!empty($data["categoria"])) {
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
