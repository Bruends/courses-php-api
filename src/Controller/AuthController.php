<?php

namespace CoursesApi\Controller;

use CoursesApi\Model\UserModel;
use CoursesApi\Classes\User;
use CoursesApi\Utils\ValidateParams;
use Exception;
use Firebase\JWT\JWT;

class AuthController
{
    public static function authenticate($request, $response)
    {
        try {
            $params = $request->getParsedBody();
            ValidateParams::loginRequest($params);

            // getting user from db
            $userModel = new UserModel();
            $user = $userModel->getUser($params["username"]);

            // check password match
            $checkPassword = password_verify($params["password"], $user->__get("password"));

            // password does't match
            if (!$checkPassword) {
                throw new Exception("wrong password!", 401);
            }

            // generate jwt with user id
            $jwtPayload = ["userId" => $user->__get("id")];
            $jwt = JWT::encode($jwtPayload, $_ENV["JWT_KEY"], 'HS256');

            // returning token
            $response->getBody()->write(
                json_encode(["token" => $jwt])
            );

            // success
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $e) {
            // getting error code
            $errorStatus = $e->getCode();
            if ($errorStatus == null) {
                $errorStatus = 500;
            }

            $response->getBody()->write(json_encode([
                "message" => $e->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($e->getCode());
        }
    }

    public static function registerUser($request, $response)
    {
        try {
            $params = $request->getParsedBody();
            ValidateParams::registerRequest($params);
            // hashing password
            $password = password_hash($params["password"], PASSWORD_ARGON2ID);

            // create user
            $user = new User($params["username"], $params["email"], $password);

            //saving user on DB
            $userModel = new UserModel();
            $userModel->register($user);

            return $response
                ->withStatus(201);
        } catch (Exception $e) {
            // getting error code
            $errorStatus = $e->getCode();
            if ($errorStatus == null) {
                $errorStatus = 500;
            }

            $response->getBody()->write(json_encode([
                "message" => $e->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($e->getCode());
        }
    }
}
