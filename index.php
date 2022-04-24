<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once "config.php";
// testing
use CoursesApi\Model\CourseModel;
use CoursesApi\Classes\Category;
use CoursesApi\Classes\Course;

$cat = new Category(66, "tech");
$c = new Course("test", "www.teste.com", $cat);
$cm = new CourseModel(11, $c);

print_r($cm->getAll(11));
$c->__set("name", "teste2");
$c->__set("link", "t2.com");
$c->__set("id", 29);
print_r($cm->update(11, $c));
print_r($cm->getAll(11));