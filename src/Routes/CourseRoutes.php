<?php

namespace CoursesApi\Routes;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use CoursesApi\Controller\CourseController;

class CourseRoutes
{
  public static function addRoutes ($app) {
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
  }
}
