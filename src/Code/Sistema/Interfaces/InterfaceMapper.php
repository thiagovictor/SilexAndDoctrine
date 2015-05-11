<?php

namespace Code\Sistema\Interfaces;

interface InterfaceMapper {

    public function insert($entity);

    public function update($entity);

    public function delete($id);
    
    public function findAll();
    
    public function find($id);
}
