<?php

namespace CoursesApi\Controller;

use CoursesApi\Model\CourseModel;
use CoursesApi\Model\CategoryModel;
use CoursesApi\Classes\Course;
use CoursesApi\Utils\Token;
use CoursesApi\Utils\ValidateParams;
use Exception;

class CourseController
{
  public static function getAllCourses($request, $response) {
    try {
      // getting user id from jwt token
      $tokenParam = $request->getHeaderLine("Authorization");      
      $userId = Token::decodeAndGetUserId($tokenParam);
      
      // getting courses
      $courseModel = new CourseModel();
      $courses = $courseModel->getAll($userId);

      $response->getBody()->write(json_encode($courses));
      return $response;

    } catch (Exception $e) {
      $errorStatus = $e->getCode();
      if($errorStatus == null){
        $errorStatus = 500;
      }

      return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($errorStatus);
    }
  } 

  public static function getCourseById($request, $response, $args) {
    try {
      // getting user id from jwt token
      $tokenParam = $request->getHeaderLine("Authorization");      
      $userId = Token::decodeAndGetUserId($tokenParam);
      
      // getting courses
      $courseModel = new CourseModel();
      $courses = $courseModel->getById($userId, $args["id"]);

      $response->getBody()->write(json_encode($courses));
      return $response;

    } catch (Exception $e) {
      $errorStatus = $e->getCode();
      if($errorStatus == null){
        $errorStatus = 500;
      }

      return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($errorStatus);
    }
  }

  public static function saveCourse($request, $response) {
    try {
      // getting user id from jwt token
      $tokenParam = $request->getHeaderLine("Authorization");      
      $userId = Token::decodeAndGetUserId($tokenParam);

      // getting and validating course
      $courseParams = $request->getParsedBody();
      ValidateParams::courseRequest($courseParams);

      // getting category from DB
      $categoryName = $courseParams["category"];
      $categoryModel = new CategoryModel;
      $category = $categoryModel->getCategoryOrSaveNew($categoryName);
      
      $course = new Course(
        $courseParams["name"],
        $courseParams["link"],
        $category,
      );
      
      // saving course
      $courseModel = new CourseModel();
      $courseModel->save($userId, $course);    

      return $response
        ->withStatus(201);

    } catch (Exception $e) {
      // getting error code
      $errorStatus = $e->getCode();
      if($errorStatus == null)
        $errorStatus = 500;
        
      $response->getBody()->write(json_encode([
        "message" => $e->getMessage()
      ]));

      return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($e->getCode());
    }
  }

  public static function updateCourse($request, $response) {
    try { 
      // getting user id from jwt token
      $tokenParam = $request->getHeaderLine("Authorization");      
      $userId = Token::decodeAndGetUserId($tokenParam);

      // getting and validating course
      $courseParams = $request->getParsedBody();
      ValidateParams::courseRequest($courseParams);

      // getting category from DB
      $categoryName = $courseParams["category"];
      $categoryModel = new CategoryModel;
      $category = $categoryModel->getCategoryOrSaveNew($categoryName);
      
      $course = new Course(
        $courseParams["name"],
        $courseParams["link"],
        $category,
        $courseParams["id"],
      );
      
      // getting courses
      $courseModel = new CourseModel();
      $courseModel->update($userId, $course);

      return $response
        ->withStatus(200);

      } catch (Exception $e) {
        // getting error code
        $errorStatus = $e->getCode();
        if($errorStatus == null)
          $errorStatus = 500;
        
        $response->getBody()->write(json_encode([
          "message" => $e->getMessage()
        ]));

        return $response
          ->withHeader('Content-Type', 'application/json')
          ->withStatus($e->getCode());
      }
  }

  public static function deleteCourse($request, $response, $args) {
    try {
      // getting user id from jwt token
      $tokenParam = $request->getHeaderLine("Authorization");      
      $userId = Token::decodeAndGetUserId($tokenParam);
      
      // deleting course courses
      $courseModel = new CourseModel();
      $courseModel->delete($userId, $args["id"]);

      return $response;

    } catch (Exception $e) {
      $errorStatus = $e->getCode();
      if($errorStatus == null){
        $errorStatus = 500;
      }

      return $response        
        ->withStatus($errorStatus);
    }
  }
}