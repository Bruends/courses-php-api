<?php

namespace CoursesApi\Model;

use CoursesApi\Model\DB;
use CoursesApi\Classes\User;
use Exception;
use PDOException;

class UserModel
{
  private $db;

  public function __construct() {
    $this->db = new DB();
  }

  public function getUser($username) {    
    try {
      $query = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
      $res = $this->db->preparedQueryAndFetch($query, [$username]);
        
      // user not found
      if(!sizeof($res) == 1){
        throw new Exception("user not found!", 400);
      }

      // returning the user
      $user = new User(
        $res[0]["username"], 
        $res[0]["email"], 
        $res[0]["password"], 
        $res[0]["id"]
      );
        
      return $user;
    } catch (PDOException $e) {
      throw new Exception($e->getMessage(), 500);
    }
  }

  public function register($user) {
    try {
      $query = "INSERT INTO users(username, email, password) VALUES (?,?,?)";
      
      $values = [
        $user->__GET("username"),
        $user->__GET("email"),
        $user->__GET("password")
      ];

      $result = $this->db->preparedQuery($query, $values);
      return $result;
    } catch (PDOException $e) {
      // SQL duplicated entry error
      if ($e->getCode() == 23000) {
        // username
        if (strpos($e->getMessage(),"username") != false) 
          throw new Exception("username already in use", 400);
        
        // email
        if (strpos($e->getMessage(),"email") != false) 
          throw new Exception("email already in use", 400);
      }
      
      throw new Exception($e->getMessage(), 500);
    }
  }
}
