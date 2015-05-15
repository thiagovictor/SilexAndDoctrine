<?php

namespace Code\Sistema\Service;

use Doctrine\ORM\EntityManager;
use Code\Sistema\Entity\Produto;

class ProdutoService {

    private $em;
    private $produto;
    private $message = array();
    private $validators = array();

    public function __construct(EntityManager $em, Produto $produto) {
        $this->em = $em;
        $this->produto = $produto;
    }

    public function checkValidator($metodo, $valor) {
        foreach ($this->validators as $key => $validator) {
            if ($metodo != $key) {
                continue;
            }
            if (!$validator->isValid($valor)) {
                $this->message[] = strtoupper($key)." : ".$validator->getMessage();
                return false;
            }
        }
        return true;
    }

    private function popular(array $data = array()) {
        foreach ($data as $metodo => $valor) {
            if (!$this->checkValidator($metodo, $valor)) {
                return false;
            }
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
        return false;
    }

    public function update(array $data = array()) {
        if (!isset($data["id"])) {
            $this->setMessage("Parametro :id nao encontrado");
            return false;
        }
        $this->produto = $this->em->getReference("Code\Sistema\Entity\Produto", $data["id"]);
        if (!$this->popular($data)) {
            $this->setMessage("N�o foi possivel popular a entidade");
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
        $this->message[] = $message;
        return $this;
    }

    function setValidators($indice, $validator) {
        $this->validators[$indice] = $validator;
        return $this;
    }

    function setArrayValidators(array $validators) {
        $this->validators = $validators;
        return $this;
    }

}
