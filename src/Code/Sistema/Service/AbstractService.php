<?php

namespace Code\Sistema\Service;

use Doctrine\ORM\EntityManager;

abstract class AbstractService {

    protected $em;
    protected $message = array();
    protected $validators = array();
    protected $object;
    protected $entity;

    public function __construct(EntityManager $em) {
        $this->em = $em;
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
    
    public function ajustaData(array $data = array()) {
        return $data;
    }
    
    private function popular(array $data = array()) {
        $data_checked = $this->ajustaData($data);
        foreach ($data_checked as $metodo => $valor) {
            if (!$this->checkValidator($metodo, $valor)) {
                return false;
            }
            $metodo = 'set' . ucfirst($metodo);
            if (!method_exists($this->object, $metodo)) {
                $this->setMessage("Nao foi possivel converte-lo, verifique os atributos enviados");
                return false;
            }
            $this->object->$metodo($valor);
        }
        return $this->object;
    }

    public function insert(array $data = array()) {
        if ($this->popular($data)) {
            $this->em->persist($this->object);
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
        $this->object = $this->em->getReference($this->entity, $data["id"]);
        if (!$this->popular($data)) {
            $this->setMessage("Não foi possivel popular a entidade");
            return false;
        }
        $this->em->persist($this->object);
        $this->em->flush();
        return true;
    }

    public function delete($id) {
        $this->object = $this->em->getReference($this->entity, $id);
        $this->em->remove($this->object);
        $this->em->flush();
        return true;
    }

    public function findAll() {
        $repo = $this->em->getRepository($this->entity);
        return $repo->findAll();
    }

    public function findPagination($firstResult, $maxResults) {
        $repo = $this->em->getRepository($this->entity);
        return $repo->findPagination($firstResult, $maxResults);
    }

    public function getRows() {
        $repo = $this->em->getRepository($this->entity);
        return $repo->getRows();
    }

    public function find($id) {
        $repo = $this->em->getRepository($this->entity);
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
