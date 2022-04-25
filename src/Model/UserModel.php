<?php

namespace CoursesApi\Model;

use CoursesApi\Model\DB;
use CoursesApi\Classes\User;

class UserModel
{
  private $db;

  public function __construct() {
    $this->db = new DB();
  }

  public function getUser($username) {    
    $query = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
    $res = $this->db->preparedQuery($query, [$username]);
      
    // user not found
    if(!sizeof($res) == 1){
      return null;
    }

    // user found
    $user = new User(
      $res[0]["username"], 
      $res[0]["email"], 
      $res[0]["password"], 
      $res[0]["id"]
    );
      
    return $user;
  }

  public function register($user) {
    $query = "INSERT INTO users(username, email, password) VALUES (?,?,?)";
      
    $values = [
      $user->__GET("username"),
      $user->__GET("email"),
      $user->__GET("password")
    ];

    $result = $this->db->preparedQuery($query, $values);
    return $result;
  }
}
