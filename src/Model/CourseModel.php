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

            $results = $this->db->preparedQuery($query, [$user_id]);
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

            $result = $this->db->preparedQuery($query, [$user_id, $course_id]);

            return $result;

        } catch(PDOException $error) {
            print_r($error);
        }
    }

    public function delete($user_id, $course_id) {
        try{
            $query = "DELETE FROM courses WHERE id = ? AND user_id = ?";
            $con = Connection::create();
            $state = $con->prepare($query);
            $state->execute([$course_id, $user_id]);
        } catch(PDOException $error) {
            print_r($error);
        }
    }
}
