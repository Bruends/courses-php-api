<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once "config.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Tuupola\Middleware\JwtAuthentication;

use CoursesApi\Controller\AuthController;
use CoursesApi\Controller\CourseController;

$app = AppFactory::create();

// middlewares
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// jwt auth middleware
$app->add(new JwtAuthentication([
  "path" => "/courses",
  "secret" => JWT_KEY
]));


$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// auth routes
$app->post('/auth/register', function (Request $request, Response $response) {
  return AuthController::registerUser($request, $response);
});

$app->post('/auth/login', function (Request $request, Response $response) {
  return AuthController::authenticate($request, $response);
});

// course routes
$app->get('/courses', function (Request $request, Response $response) {
  return CourseController::getAllCourses($request, $response);
});

$app->get('/courses/{id}', function (Request $request, Response $response, $args) {
  return CourseController::getCourseById($request, $response, $args);
});

$app->post('/courses', function (Request $request, Response $response) {
  return CourseController::saveCourse($request, $response);
});

$app->put('/courses', function (Request $request, Response $response) {
  return CourseController::updateCourse($request, $response);
});

$app->delete('/courses/{id}', function (Request $request, Response $response, $args) {
  return CourseController::deleteCourse($request, $response, $args);
});

$app->run();