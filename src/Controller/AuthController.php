<?php

namespace CoursesApi\Controller;

use CoursesApi\Model\UserModel;
use CoursesApi\Classes\User;
use Firebase\JWT\JWT;

class AuthController
{
  public static function authenticate($request, $response) {
    $params = $request->getParsedBody();
    
    // getting user from db
    $userModel = new UserModel();
    $user = $userModel->getUser($params["username"]);

    // user not found
    if($user == null){
      $message = json_encode(["msg" => "usuário ou senha incorretos!"]);
      $response->getBody()->write($message);

      return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(401);
    }
    
    // check password match
    $checkPassword = password_verify($params["password"], $user->__get("password"));

    // password does't match
    if(!$checkPassword) {
      $message = json_encode(["msg" => "usuário ou senha incorretos!", "pass" => $user->__get("password")]);
      $response->getBody()->write($message);

      return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(401);
    }
    
    // generate jwt with user id
    $jwtPayload = ["userId" => $user->__get("id")];
    $jwt = JWT::encode($jwtPayload, JWT_KEY, 'HS256');

    // returning token
    $response->getBody()->write(
      json_encode(["token" => $jwt])
    );

    return $response      
      ->withHeader('Content-Type', 'application/json')
      ->withStatus(200);
  }

  public static function registerUser($request, $response) {
    $params = $request->getParsedBody();
    
    // hashing password
    $password = password_hash($params["password"], PASSWORD_ARGON2ID);    

    // create user
    $user = new User($params["username"], $params["email"], $password);

    //saving user on DB
    $userModel = new UserModel();
    $userModel->register($user);

    return $response      
      ->withStatus(201);
  }
}
