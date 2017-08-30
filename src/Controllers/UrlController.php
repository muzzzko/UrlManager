<?php
namespace UrlManager\Controllers;

use UrlManager\Services\UserService;
use UrlManager\Services\UrlService;
use UrlManager\Services\TransitionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UrlController extends AbstractController
{
    private $urlService;
    private $transitionService;



    public function __construct(
          UserService $userService,
          UrlService $urlService,
          TransitionService $transitionService
    ) {
        parent::__construct($userService);
        $this->urlService = $urlService;
        $this->transitionService = $transitionService;
    }



    public function createUserShortenUrl(Request $request)
    {
        try {
            $user = $this->getUserByAuthorization($request);
            if ($user === false) {
                return $this->createUnauthorizedResponse();
            }

            $sourceUrl = $request->get('url');

            $shortenUrl = $this->urlService->createUserShortenUrl(
                $sourceUrl,
                $user
              );
        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }

        return new JsonResponse(
            [
              'shortenUrl' => $shortenUrl->getShortenUrl(),
              'id' => $shortenUrl->getId()
            ],
            Response::HTTP_CREATED
        );
    }

    public function getAllUserShortenUrls(Request $request)
    {
        try {
            $user = $this->getUserByAuthorization($request);
            if ($user === false) {
                return $this->createUnauthorizedResponse();
            }

            $shortenUrls = $this->urlService->getAllUserShortenUrls($user);
        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }

        if ($shortenUrls == null) {
            return new JsonResponse(
                [],
                Response::HTTP_NO_CONTENT
            );
        }

        $result = [];
        foreach ($shortenUrls as $shortenUrl) {
           $result[] = [
               'shortenUrl' => $shortenUrl->getShortenUrl(),
               'sourceUrl' => $shortenUrl->getSourceUrl(),
               'id' => $shortenUrl->getId()
           ];
        }

        return new JsonResponse(
            $result
        );
    }

    public function getUserShortenUrl(Request $request, $id)
    {
        try {

            $user = $this->getUserByAuthorization($request);
            if ($user === false) {
                return $this->createUnauthorizedResponse();
            }

            $shortenUrl = $this->urlService->getUserShortenUrlById($user, $id);

            if ($shortenUrl == null) {
                return new JsonResponse(
                    [],
                    Response::HTTP_NO_CONTENT
                );
            }
            $countTransitions = $this
                                    ->transitionService
                                    ->getCountTransitionsUserShortenUrl($id);

        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }

        return new JsonResponse(
            [
                "shortenUrl" => "app/v1/shorten_urls/".$shortenUrl->getHash(),
                "sourceUrl" => $shortenUrl->getSourceUrl(),
                "countTransitions" => $countTransitions
            ]
        );

    }

    public function deleteUserShortenUrl(Request $request, $id)
    {
        try {

            $user = $this->getUserByAuthorization($request);
            if ($user === false) {
                return $this->createUnauthorizedResponse();
            }

            $this->urlService->deleteUserShortenUrl($id);

        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }

        return new JsonResponse();
    }
}
