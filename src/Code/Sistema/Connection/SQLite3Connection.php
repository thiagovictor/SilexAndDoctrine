<?php

namespace Code\Sistema\Connection;

use Code\Sistema\Interfaces\InterfaceConnection;

class SQLite3Connection implements InterfaceConnection {

    private $db;

    public function __construct(\SQLite3 $db) {
        $this->db = $db;
    }

    public function getConnection() {
        return $this->db;
    }

}
