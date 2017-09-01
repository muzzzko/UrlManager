<?php
namespace UrlManager\Services;

use UrlManager\Repositories\UserRepository;
use UrlManager\Servicies\EmailService;
use UrlManager\Models\User;
use UrlManager\Models\Password;

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
        $password = new Password($password);

        $user = new User($name, $password, $email);

        return $this->userRepository->saveUser($user);
    }

    public function getUserByAuthorization($email, $password)
    {
        if (!filter_var($email,FILTER_VALIDATE_EMAIL))
            throw new \InvalidArgumentException('Email is wrong');

        $password = new Password($password, true, false);

        $user = $this->userRepository->getUserByEmail($email);

        return ($user !== null &&
            $user->getPassword() === $password->getPassword()) ? $user : false;
    }


}
