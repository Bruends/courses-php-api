<?php

namespace CoursesApi\Classes;

class User 
{
  private $id;
  private $name;
  private $email;
  private $password;

  public function __construct($username, $email, $password, $id = null) {
    $this->username = $username;
    $this->email = $email;
    $this->password = $password;
    $this->id = $id;
  }

  public function __set($attribute, $value) {
    if(isset($value))
      $this->$attribute = $value;
  }

  public function __get($attribute) {
    return $this->$attribute;
  }
}