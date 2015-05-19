<?php

namespace Code\Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Code\Sistema\Entity\TagRepository")
 * @ORM\Table(name="tags")
 */
class Tag {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255) 
     */
    private $nome;
       
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }


}
