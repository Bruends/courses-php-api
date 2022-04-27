<?php

require_once __DIR__ . "/vendor/autoload.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Tuupola\Middleware\JwtAuthentication;
use Slim\Exception\HttpNotFoundException;

use CoursesApi\Controller\AuthController;
use CoursesApi\Controller\CourseController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();

// middlewares
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// jwt auth middleware
$app->add(new JwtAuthentication([
  "path" => "/courses",
  "secret" => $_ENV["JWT_KEY"]
]));

// cors middleware
$app->options('/{routes:.+}', function ($request, $response, $args) {
  return $response;
});

$app->add(function ($request, $handler) {
  $response = $handler->handle($request);
  return $response
    ->withHeader('Access-Control-Allow-Origin', $_ENV["ALLOWED_CORS_DOMAINS"])
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});



//error middleware
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


$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
  throw new HttpNotFoundException($request);
});

$app->run();