<?php

namespace CoursesApi\Model;

use CoursesApi\Classes\Course;
use CoursesApi\Model\DB;
use Exception;
use PDOException;

class CourseModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    // return an associative array with all books
    public function getAll(string|int $user_id): array
    {
        try {
            // getting results from db
            $query = "SELECT courses.id, courses.user_id, courses.name, courses.link, 
                categories.name AS category, courses.category_id 
                FROM courses
                JOIN categories
                ON courses.category_id = categories.id
                WHERE courses.user_id = ?";

            $results = $this->db->preparedQueryAndFetch($query, [$user_id]);
            return $results;
        } catch (PDOException $e) {
            throw new Exception("error on getting courses", 500);
        }
    }

    public function getById(string|int $user_id, string|int $course_id): array
    {
        try {
            $query = "SELECT courses.id, courses.user_id, courses.name, courses.link, 
                categories.name AS category, courses.category_id 
                FROM courses
                JOIN categories
                ON courses.category_id = categories.id
                WHERE courses.user_id = ? AND courses.id = ?";

            $result = $this->db->preparedQueryAndFetch($query, [$user_id, $course_id]);

            return $result;
        } catch (PDOException $e) {
            throw new Exception("error on getting courses", 500);
        }
    }

    public function save(string|int $user_id, Course $course): bool
    {
        try {
            $query = "INSERT INTO courses(user_id, name, link, category_id) 
                VALUES(?,?,?,?)";

            $values = [
                $user_id,
                $course->__get("name"),
                $course->__get("link"),
                $course->category->__get("id")
            ];
            $this->db->preparedQuery($query, $values);
            return true;
        } catch (PDOException $e) {
            throw new Exception("error on saving course", 500);
        }
    }

    public function update(string|int $user_id, Course $course): bool
    {
        try {
            $query = "UPDATE courses SET name = ?, link = ?, category_id = ?
                WHERE id = ? AND user_id = ?";

            $values = [
                $course->__get("name"),
                $course->__get("link"),
                $course->category->__get("id"),
                $course->__get("id"),
                $user_id
            ];

            $this->db->preparedQuery($query, $values);
            return true;
        } catch (PDOException $e) {
            throw new Exception("error on updating course", 500);
        }
    }

    public function delete(string|int $user_id, string|int $course_id): void
    {
        try {
            $query = "DELETE FROM courses WHERE user_id = ? AND id = ?";
            $this->db->preparedQuery($query, [$user_id, $course_id]);
        } catch (PDOException $e) {
            throw new Exception("error on deleting course", 500);
        }
    }
}
