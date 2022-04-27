<?php

namespace CoursesApi\Utils;

use Exception;

class ValidateParams
{
  public static function loginRequest($params) {
    if(empty($params["username"])) {
      throw new Exception("username is required!", 400);
    }
    
    if(empty($params["password"])) {
      throw new Exception("password is required!", 400);
    }

    if(strlen($params["password"]) < 6 ) {
      throw new Exception("password must have at least 6 characters!", 400);
    }
  }

  public static function registerRequest($params) {
    self::loginRequest($params);

    if (empty($params["email"])) {
      throw new Exception("email is required!", 400);
    }

    if (!filter_var($params["email"], FILTER_VALIDATE_EMAIL)) {
      throw new Exception("invalid email!", 400);
    }
  }

  public static function courseRequest($params) {
    if(empty($params["name"])) {
      throw new Exception("name is required!", 400);
    }

    if(empty($params["link"])) {
      throw new Exception("link is required!", 400);
    }

    if(empty($params["category"])) {
      throw new Exception("category is required!", 400);
    }
  }
}