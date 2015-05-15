<?php

namespace Code\Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Code\Sistema\Entity\ProdutoRepository")
 * @ORM\Table(name="produto")
 */
class Produto {
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
    
    /**
     * @ORM\Column(type="string", length=255) 
     */
    private $descricao;
    
    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $valor;
    
    public function __construct($nome="",$descricao="",$valor="",$id="") {
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->valor = $valor;
        $this->id = $id;
    }
    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getValor() {
        return $this->valor;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
        return $this;
    }

    function setValor($valor) {
        $this->valor = $valor;
        return $this;
    }


}
