<?php

namespace CoursesApi\Classes;

class Category
{
    private $id;
    private $name;

    public function __construct(string $name, string|int $id = null)
    {
        $this->id = $id;
        $this->name = $name;
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
