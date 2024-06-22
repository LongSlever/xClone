<?php

namespace MF\Model;
use PDO;
abstract class Model {
    protected $db;

        public function __construct(PDO $db) {
            $this->db = $db;
        }
}


?>