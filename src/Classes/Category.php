<?php

namespace CourseApi\Classes;

class Category 
{
  private $id;
  private $name;

  public function __construct($id, $name) {
    $this->id = $id;
    $this->name = $name;
  }

  public function __set($attribute, $value){
    if(isset($value))
      $this->$attribute = $value;
  }

  public function __get($attribute) {
    return $this->$attribute;
  }
}

