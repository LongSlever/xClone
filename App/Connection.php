<?php

namespace App;

use PDO;
use PDOException;

class Connection {
    public static function getDb () {
        try {
            $conn = new PDO(
                "mysql:host=localhost;dbname=xClone;charset=utf8",
                "root",
                ""
            );
            return $conn;
        } catch (PDOException $e) {
            echo $e;
        }
    }
}

?>