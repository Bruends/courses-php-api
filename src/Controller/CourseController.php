<?php

namespace CoursesApi\Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use CoursesApi\Model\CourseModel;
use CoursesApi\Model\CategoryModel;
use CoursesApi\Classes\Course;

class CourseController
{
  public static function getAllCourses($request, $response) {
    // getting header
    $tokenParam = $request->getHeaderLine("Authorization");
    
    // decoding token
    // and getting the userId
    $token = explode(" ", $tokenParam)[1];
    $decoded = (array) JWT::decode($token, new Key(JWT_KEY, 'HS256'));   
    $userId = $decoded["userId"];
    
    // getting courses
    $courseModel = new CourseModel();
    $courses = $courseModel->getAll($userId);

    $response->getBody()->write(json_encode($courses));
    return $response;
  } 

  public static function getCourseById($request, $response, $args) {
    // getting header     
    $tokenParam = $request->getHeaderLine("Authorization");
    
    // decoding token and getting the user ID
    $token = explode(" ", $tokenParam)[1];    
    $decoded = (array) JWT::decode($token, new Key(JWT_KEY, 'HS256'));   
    $userId = $decoded["userId"];
    
    // getting courses
    $courseModel = new CourseModel();
    $courses = $courseModel->getById($userId, $args["id"]);

    $response->getBody()->write(json_encode($courses));
    return $response;
  }

  public static function saveCourse($request, $response) {
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
    $decoded = (array) JWT::decode($token, new Key(JWT_KEY, 'HS256'));   
    $userId = $decoded["userId"];
    
    // getting courses
    $courseModel = new CourseModel();
    $courseModel->save($userId, $course);    

    return $response
      ->withStatus(201);
  }

  public static function updateCourse($request, $response) {
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
    $decoded = (array) JWT::decode($token, new Key(JWT_KEY, 'HS256'));   
    $userId = $decoded["userId"];
    
    // getting courses
    $courseModel = new CourseModel();
    $courseModel->update($userId, $course);

    return $response
      ->withStatus(201);
  }

  public static function deleteCourse($request, $response, $args) {
    // getting header     
    $tokenParam = $request->getHeaderLine("Authorization");
    
    // decoding token and getting the user ID
    $token = explode(" ", $tokenParam)[1];    
    $decoded = (array) JWT::decode($token, new Key(JWT_KEY, 'HS256'));   
    $userId = $decoded["userId"];
    
    // deleting course courses
    $courseModel = new CourseModel();
    $courseModel->delete($userId, $args["id"]);

    return $response;
  }
}