<?php

namespace Code\Sistema\Mapper;

use Code\Sistema\Entity\Produto;
use Code\Sistema\Interfaces\InterfaceMapper,
    Code\Sistema\Interfaces\InterfaceConnection;

class ProdutoMapper implements InterfaceMapper {

    private $db;
    private $produto;

    public function __construct(InterfaceConnection $conexao) {
        $this->db = $conexao->getConnection();
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM produto WHERE id = ?');
        $stmt->bindValue(1, $id, SQLITE3_INTEGER);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function find($id) {
        $stmt = $this->db->prepare('SELECT * FROM produto WHERE id = ?');
        $stmt->bindValue(1, $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if ($result) {
            return $result->fetchArray(SQLITE3_ASSOC);
        }
        return false;
    }

    public function insert($entity) {
        if (!$entity instanceof Produto) {
            return false;
        }
        $stmt = $this->db->prepare('INSERT INTO produto (nome, descricao, valor) VALUES (?,?,?)');
        $stmt->bindValue(1, $entity->getNome(), SQLITE3_TEXT);
        $stmt->bindValue(2, $entity->getDescricao(), SQLITE3_TEXT);
        $stmt->bindValue(3, floatval($entity->getValor()), SQLITE3_FLOAT);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update($entity) {
        if (!$entity instanceof Produto) {
            return false;
        }
        $stmt = $this->db->prepare('UPDATE produto SET nome = ?, descricao = ?, valor = ? WHERE id = ?');
        $stmt->bindValue(1, $entity->getNome(), SQLITE3_TEXT);
        $stmt->bindValue(2, $entity->getDescricao(), SQLITE3_TEXT);
        $stmt->bindValue(3, floatval($entity->getValor()), SQLITE3_FLOAT);
        $stmt->bindValue(4, $entity->getId(), SQLITE3_INTEGER);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function findAll() {
        $result = $this->db->query('select * from produto');
        $produtos = array();
        while ($produto = $result->fetchArray()) {
            $produtos[]= [
                "id"=>$produto["id"],
                "nome"=>$produto["nome"],
                "descricao"=>$produto["descricao"],
                "valor"=>number_format($produto["valor"], 2, ',', '.'),
            ];
        }
        return $produtos;
    }

}
