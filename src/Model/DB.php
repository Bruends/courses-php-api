<?php

namespace CoursesApi\Model;

use PDO;

class DB
{
    private $pdo;

    public function __construct()
    {
        $conString = "mysql:host=" . $_ENV["DB_HOST"] . ";dbname=" . $_ENV["DB_NAME"];
        $pdo = new PDO($conString, $_ENV["DB_USER"], $_ENV["DB_PASS"]);
        $this->pdo = $pdo;
    }

    public function preparedQueryAndFetch($query, $values)
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function preparedQuery($query, $values)
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $stmt;
    }
}
