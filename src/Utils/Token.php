<?php

namespace CoursesApi\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class Token
{
  public static function decodeAndGetUserId($encodedToken) {
    // removing Bearer
    $token = explode(" ", $encodedToken)[1];
    //decoding and returning
    $decoded = (array) JWT::decode($token, new Key($_ENV["JWT_KEY"], 'HS256'));   
    return $decoded["userId"];
  }
}