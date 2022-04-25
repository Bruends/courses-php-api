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

    public function preparedQueryAndFetch($query, $values) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function preparedQuery($query, $values){
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $stmt;
    }
}
