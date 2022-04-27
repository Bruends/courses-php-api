<?php

namespace CoursesApi\Routes;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use CoursesApi\Controller\AuthController;

class AuthRoutes
{
  public static function addRoutes($app) {
    $app->post('/auth/register', function (Request $request, Response $response) {
      return AuthController::registerUser($request, $response);
    });
    
    $app->post('/auth/login', function (Request $request, Response $response) {
      return AuthController::authenticate($request, $response);
    });
  }
}
