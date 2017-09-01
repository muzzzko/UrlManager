<?php
namespace UrlManager\Models;

class User
{
    private $name;
    private $password;
    private $email;
    private $id;



    public function __construct($name,Password $password, $email, $id = null)
    {
        if ($name === '')
            throw new \InvalidArgumentException('Name is clear');

        if (!filter_var($email,FILTER_VALIDATE_EMAIL))
          throw new \InvalidArgumentException('Email is wrong');

        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->id = $id;
    }



    public function getName()
    {
        return $this->name;
    }

    public function getPassword()
    {
        return $this->password->getPassword();
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getId()
    {
        return $this->id;
    }
}
