<?php

namespace Code\Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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

    /**
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $categoria;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="produtos_tags",
     * joinColumns={@ORM\JoinColumn(name="produto_id", referencedColumnName="id", onDelete="CASCADE")},
     * inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     * */
    private $tags;

    public function __construct($nome = "", $descricao = "", $valor = "", $id = "") {
        $this->tags = new ArrayCollection();
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

    function getCategoria() {
        return $this->categoria;
    }

    function setCategoria($categoria) {
        $this->categoria = $categoria;
        return $this;
    }

    function getTags() {
        return $this->tags->toArray();
    }

    public function eraseTags() {
        $this->tags = new ArrayCollection();
    }

    function setTag(Tag $tag) {
        $this->tags->add($tag);
        return $this;
    }

    function setTags(array $tags = array()) {
        foreach ($tags as $tag) {
            $this->tags->add($tag);
        }
        return $this;
    }

}
