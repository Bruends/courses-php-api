<?php

namespace CoursesApi\Model;

use CoursesApi\Model\DB;

class UserModel
{
  private $db;

  public function __construct() {
    $this->db = new DB();
  }

  public function getUser($username) {
    try {
      $query = "SELECT id, username, password FROM user WHERE username = ? LIMIT 1";
      $result = $this->db->preparedQuery($query, [$username]);
      return $result;

    } catch (Exception $error) {
      print_r($error);
    }

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
