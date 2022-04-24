<?php

namespace CourseApi\Classes;


class Course
{
    private $id;
    private $name;
    private $link;
    private $category;

    public function __construct($name, $link, $category, $id = null){
        $this->name = $name;
        $this->link = $link;
        $this->category = $category;
        $this->id = $id;
    }

    public function assocArrayToCourse($courseArray) {
        foreach ($courseArray as $key => $value) {
            $this->__set($key, $value);
        }
    }

    public function __set($attribute, $value) {
        if(isset($value))
            $this->$attribute = $value;
    }

    public function __get($attribute) {
        return $this->$attribute;
    }
}
