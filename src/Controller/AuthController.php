<?php

namespace CoursesApi\Controller;

use CoursesApi\Model\UserModel;
use CoursesApi\Classes\User;

class AuthController
{
  public static function getToken($request, $response) {
    
  }

  public static function registerUser($request, $response) {
    $params = $request->getParsedBody();
    
    // hashing password
    $password = password_hash($reqUser["password"], PASSWORD_BCRYPT);    

    // create user
    $user = new User($params["username"], $params["email"], $password);

    //saving user on DB
    $userModel = new UserModel();
    $userModel->register($user);

    $response->getBody()->write("");
    return $response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus(201);
  }
}