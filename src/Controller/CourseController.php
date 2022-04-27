<?php

namespace CoursesApi\Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use CoursesApi\Model\CourseModel;
use CoursesApi\Model\CategoryModel;
use CoursesApi\Classes\Course;
use Exception;

class CourseController
{
  public static function getAllCourses($request, $response) {
    try {
      // getting header
      $tokenParam = $request->getHeaderLine("Authorization");
      
      // decoding token
      // and getting the userId
      $token = explode(" ", $tokenParam)[1];
      $decoded = (array) JWT::decode($token, new Key($_ENV["JWT_KEY"], 'HS256'));   
      $userId = $decoded["userId"];
      
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
      // getting header     
      $tokenParam = $request->getHeaderLine("Authorization");
      
      // decoding token and getting the user ID
      $token = explode(" ", $tokenParam)[1];    
      $decoded = (array) JWT::decode($token, new Key($_ENV["JWT_KEY"], 'HS256'));   
      $userId = $decoded["userId"];
      
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
      // getting header
      $courseParams = $request->getParsedBody();
      $tokenParam = $request->getHeaderLine("Authorization");

      // getting category from DB
      $categoryName = $courseParams["category"];
      $categoryModel = new CategoryModel;
      $category = $categoryModel->getCategoryOrSaveNew($categoryName);
      
      $course = new Course(
        $courseParams["name"],
        $courseParams["link"],
        $category,
      );
      
      // decoding token and getting the user ID
      $token = explode(" ", $tokenParam)[1];    
      $decoded = (array) JWT::decode($token, new Key($_ENV["JWT_KEY"], 'HS256'));   
      $userId = $decoded["userId"];
      
      // saving course
      $courseModel = new CourseModel();
      $courseModel->save($userId, $course);    

      return $response
        ->withStatus(201);

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

  public static function updateCourse($request, $response) {
    try { 
      // getting header
      $courseParams = $request->getParsedBody();
      $tokenParam = $request->getHeaderLine("Authorization");

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
      
      // decoding token and getting the user ID
      $token = explode(" ", $tokenParam)[1];    
      $decoded = (array) JWT::decode($token, new Key($_ENV["JWT_KEY"], 'HS256'));   
      $userId = $decoded["userId"];
      
      // getting courses
      $courseModel = new CourseModel();
      $courseModel->update($userId, $course);

      return $response
        ->withStatus(200);

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

  public static function deleteCourse($request, $response, $args) {
    try {
      // getting header     
      $tokenParam = $request->getHeaderLine("Authorization");
      
      // decoding token and getting the user ID
      $token = explode(" ", $tokenParam)[1];    
      $decoded = (array) JWT::decode($token, new Key($_ENV["JWT_KEY"], 'HS256'));   
      $userId = $decoded["userId"];
      
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