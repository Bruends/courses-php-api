<?php

namespace CoursesApi\Model;

use PDO;

class DB {
    private $pdo;

    public function __construct() {
        $conString = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $pdo = new PDO($conString, DB_USER, DB_PASS);
        $this->pdo = $pdo;        
    }

    public function preparedQuery($query, $values) {
        $prepare = $this->pdo->prepare($query);
        $prepare->execute($values);
        $result = $prepare->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
