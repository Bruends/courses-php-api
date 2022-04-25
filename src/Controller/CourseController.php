<?php

namespace CoursesApi\Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use CoursesApi\Model\CourseModel;

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