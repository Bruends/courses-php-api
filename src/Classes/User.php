<?php

namespace CoursesApi\Classes;

class User
{
    private $id;
    private $name;
    private $email;
    private $password;

    public function __construct(
        string $username, 
        string|null $email, 
        string $password, 
        string|int|null $id = null
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->id = $id;
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
