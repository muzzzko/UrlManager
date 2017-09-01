<?php
namespace UrlManager\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UrlManager\Services\UserService;

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
        $password = $request->server->get('PHP_AUTH_PW');

        return $this->userService->getUserByAuthorization($email, $password);
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
