<?php
namespace UrlManager\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UrlManager\Services\UserService;
use UrlManager\Models\Password;

class AbstractController
{
    protected $userService;



    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }




    protected function getUserByAuthorization(Request $request)
    {
        $email = $request->server->get('PHP_AUTH_USER');
        $password = new Password($request->server->get('PHP_AUTH_PW'));

        $user = $this->userService->getUserByEmail($email);

        return ($user !== null &&
                $user->getPassword() === $password->getPassword()) ? $user : false;
    }

    protected function createUnauthorizedResponse()
    {
        return new JsonResponse(
            ['error' => 'not authorized'],
            Response::HTTP_UNAUTHORIZED,
            [
                'WWW-Authorization' => 'Basic realm="UrlManager API"'
            ]
        );
    }

    protected function createErrorResponse($message)
    {
        return new JsonResponse(
            ['error' => $message],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
