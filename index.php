<?php

require_once __DIR__ . "/vendor/autoload.php";

use Slim\Factory\AppFactory;
use Tuupola\Middleware\JwtAuthentication;
use Slim\Exception\HttpNotFoundException;

use CoursesApi\Routes\AuthRoutes;
use CoursesApi\Routes\CourseRoutes;

// loading .env
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
AuthRoutes::addRoutes($app);

// course routes
CourseRoutes::addRoutes($app);

// cors map
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    throw new HttpNotFoundException($request);
});

$app->run();
