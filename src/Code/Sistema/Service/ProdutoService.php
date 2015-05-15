<?php

namespace Code\Sistema\Service;

use Doctrine\ORM\EntityManager;
use Code\Sistema\Entity\Produto;

class ProdutoService {

    private $em;
    private $produto;
    private $message;

    public function __construct(EntityManager $em, Produto $produto) {
        $this->em = $em;
        $this->produto = $produto;
    }

    private function popular(array $data = array()) {
        foreach ($data as $metodo => $valor) {
            $metodo = 'set' . ucfirst($metodo);
            if (!method_exists($this->produto, $metodo)) {
                $this->setMessage("Nao foi possivel converte-lo para um produto valido. Verifique os campos enviados: nome:descricao:valor");
                return false;
            }
            $this->produto->$metodo($valor);
        }
        return $this->produto;
    }

    public function insert(array $data = array()) {
        if ($this->popular($data)) {
            $this->em->persist($this->produto);
            $this->em->flush();
            return true;
        }
        $this->setMessage("Não foi possivel popular a entidade");
        return false;
    }

    public function update(array $data = array()) {
        if (!isset($data["id"])) {
            $this->setMessage("Parametro :id nao encontrado");
            return false;
        }
        $this->produto = $this->em->getReference("Code\Sistema\Entity\Produto", $data["id"]);
        if (!$this->popular($data)) {
            $this->setMessage("Não foi possivel popular a entidade");
            return false;
        }
        $this->em->persist($this->produto);
        $this->em->flush();
        return true;
    }

    public function delete($id) {
        $this->produto = $this->em->getReference("Code\Sistema\Entity\Produto", $id);
        $this->em->remove($this->produto);
        $this->em->flush();
        return true;
    }

    public function findAll() {
        $repo = $this->em->getRepository("Code\Sistema\Entity\Produto");
        return $repo->findAll();
    }

    public function findPagination($firstResult, $maxResults) {
        $repo = $this->em->getRepository("Code\Sistema\Entity\Produto");
        return $repo->findPagination($firstResult, $maxResults);
    }

    public function getRows() {
        $repo = $this->em->getRepository("Code\Sistema\Entity\Produto");
        return $repo->getRows();
    }

    public function find($id) {
        $repo = $this->em->getRepository("Code\Sistema\Entity\Produto");
        return $repo->find($id);
    }

    function getMessage() {
        return $this->message;
    }

    function setMessage($message) {
        $this->message = $message;
        return $this;
    }

}
