<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once "config.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use CoursesApi\Controller\AuthController;

$app = AppFactory::create();

// middlewares
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// auth routes
$app->post('/auth/register', function (Request $request, Response $response) {
  return AuthController::registerUser($request, $response);
});

$app->post('/auth/login', function (Request $request, Response $response) {
  return AuthController::authenticate($request, $response);
});

$app->run();