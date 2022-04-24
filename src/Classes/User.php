<?php

namespace CoursesApi\Classes;

class User 
{
  private $id;
  private $name;
  private $email;
  private $password;

  public function __set($attribute, $value) {
    if(isset($value))
      $this->$attribute = $value;
  }

  public function __get($attribute) {
    return $this->$attribute;
  }
}