<?php

namespace CoursesApi\Model;

use CoursesApi\Model\DB;
use CoursesApi\Classes\Course; 
use PDO;
use PDOException;


class CourseModel
{
    private $db;

    public function __construct(){
        $this->db = new DB();
    }

    // return an associative array with all books
    public function getAll($user_id) {
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
        } catch (PDOException $error) {
            print_r($error);            
        }
    }

    public function getById($user_id, $course_id) {
        try{
            $query = "SELECT courses.id, courses.user_id, courses.name, courses.link, 
                categories.name AS category, courses.category_id 
                FROM courses
                JOIN categories
                ON courses.category_id = categories.id
                WHERE courses.user_id = ? AND courses.id = ?";            

            $result = $this->db->preparedQueryAndFetch($query, [$user_id, $course_id]);

            return $result;

        } catch(PDOException $error) {
            print_r($error);
        }
    }

    public function save($user_id, $course) {
        try {
            $query = "INSERT INTO courses(user_id, name, link, category_id) 
                VALUES(?,?,?,?)";

            $values = [
                $user_id,
                $course->__get("name"),
                $course->__get("link"),
                $course->category->__get("id")
            ];
            $result = $this->db->preparedQuery($query, $values); 
            return $result;
        } catch (PDOException $error) {
            print_r($error);
        }
    }

    public function update($user_id, $course) {
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
            
            $result = $this->db->preparedQuery($query, $values);
            return $result;
        } catch (PDOException $error) {
            print_r($error);
        }
    }

    public function delete($user_id, $course_id) {
        try{
            $query = "DELETE FROM courses WHERE user_id = ? AND id = ?";
            $result = $this->db->preparedQuery($query, [$user_id, $course_id]);
            return $result;
        } catch(PDOException $error) {
            print_r($error);
        }
    }
}
