<?php

namespace CoursesApi\Model;

use CoursesApi\Model\DB;
use CoursesApi\Classes\Category;

class CategoryModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function getCategory(string $categoryName): Category
    {
        $query = "SELECT id, name FROM categories WHERE name = ?";
        $result = $this->db->preparedQueryAndFetch($query, [$categoryName]);

        // if category exist return it
        if (sizeof($result) >= 1) {
            $category = new Category(
                $result[0]["name"],
                $result[0]["id"]
            );

            return $category;
        }

        // category not found
        return null;
    }

    public function saveCategory(string $categoryName): void
    {
        $query = "INSERT INTO categories(name) VALUES(?)";
        $this->db->preparedQuery($query, [$categoryName]);
    }

    public function getCategoryOrSaveNew(string $categoryName): Category
    {
        // check if category exist
        $category = $this->getCategory($categoryName);

        // save a new category otherwise
        if ($category == null) {
            $this->saveCategory($categoryName);
            $category = $this->getCategory($categoryName);
        }

        return $category;
    }
}
