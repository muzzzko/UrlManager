<?php
namespace UrlManager\Services;

use UrlManager\Repositories\UserRepository;
use UrlManager\Servicies\EmailService;
use UrlManager\Models\User;

class UserService
{
    private $userRepository;
    private $emailService;



    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }



    public function createUser($name, $password, $email)
    {
        $user = new User($name, $password, $email);

        return $this->userRepository->saveUser($user);
    }

    public function getUserByEmail($email)
    {
        if (!filter_var($email,FILTER_VALIDATE_EMAIL))
            throw new \InvalidArgumentException('Email is wrong');

        return $this->userRepository->getUserByEmail($email);
    }


}
