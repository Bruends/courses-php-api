<?php

namespace CoursesApi\Model;

use PDO;
use PDOStatement;

class DB
{
    private $pdo;

    public function __construct()
    {
        $conString = "mysql:host=" . $_ENV["DB_HOST"] . ";dbname=" . $_ENV["DB_NAME"];
        $pdo = new PDO($conString, $_ENV["DB_USER"], $_ENV["DB_PASS"]);
        $this->pdo = $pdo;
    }

    public function preparedQueryAndFetch(string $query, array $values): array|bool
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function preparedQuery(string $query, array $values): PDOStatement|bool
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $stmt;
    }
}
