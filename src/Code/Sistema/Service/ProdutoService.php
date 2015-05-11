<?php

namespace Code\Sistema\Service;

use Code\Sistema\Mapper\ProdutoMapper;
use Code\Sistema\Entity\Produto;

class ProdutoService {

    private $mapper;
    private $produto;
    private $message;

    public function __construct(ProdutoMapper $mapper, Produto $produto) {
        $this->mapper = $mapper;
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
            return $this->mapper->insert($this->produto);
        }
        return false;
    }

    public function update(array $data = array()) {
        if (!isset($data["id"])) {
            $this->setMessage("Parametro :id nao encontrado");
            return false;
        }
        if(!$this->find($data["id"])){
             $this->setMessage("Nao foi possivel localizar o registro");
            return false;
        }
        if ($this->popular($data)) {
            return $this->mapper->update($this->produto);
        }
        return false;
    }

    public function delete($id) {
        if(!$this->find($id)){
             $this->setMessage("Nao foi possivel localizar o registro");
            return false;
        }
        $result = $this->mapper->delete($id);
        if (!$result) {
            $this->setMessage("Nao foi possivel excluir o registro");
            return false;
        }
        return $result;
    }

    public function findAll() {
        return $this->mapper->findAll();
    }

    public function find($id) {
        return $this->mapper->find($id);
    }

    function getMessage() {
        return $this->message;
    }

    function setMessage($message) {
        $this->message = $message;
        return $this;
    }

}
