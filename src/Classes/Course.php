<?php

namespace CoursesApi\Classes;

use CoursesApi\Classes\Category;

class Course
{
    private $id;
    private $name;
    private $link;
    private $category;

    public function __construct(
        string $name,
        string $link,
        Category $category,
        string|int $id = null
    ) {
        $this->name = $name;
        $this->link = $link;
        $this->category = $category;
        $this->id = $id;
    }

    public function assocArrayToCourse(array $courseArray): void
    {
        foreach ($courseArray as $key => $value) {
            $this->__set($key, $value);
        }
    }

    public function __set(string $attribute, mixed $value): void
    {
        if (isset($value)) {
            $this->$attribute = $value;
        }
    }

    public function __get(string $attribute): mixed
    {
        return $this->$attribute;
    }
}
