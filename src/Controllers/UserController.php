<?php
namespace UrlManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;
use UrlManager\Services\UserService;
use Doctrine\DBAL\DBALException;
use UrlManager\Models\Password;

class UserController extends AbstractController
{
    public function register(Request $request)
    {
        try
        {
            $user = $this->userService->createUser(
                $request->get('name'),
                new Password($request->get('password')),
                $request->get('email')
            );
        } catch(DBALException $e) {
            return $this->createErrorResponse('email already exists');
        } catch(\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }

        return new JsonResponse(
            [
                'email' => $user->getEmail(),
                'name' => $user->getName()
            ],
            Response::HTTP_CREATED
        );
    }

    public function getUser(Request $request)
    {
        try {
          $user = $this->getUserByAuthorization($request);
          if ($user === false) {
              return $this->createUnauthorizedResponse();
          }
        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }



        return new JsonResponse(
            [
                'email' => $user->getEmail(),
                'name' => $user->getName()
            ]
        );

    }
}
