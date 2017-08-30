<?php
namespace UrlManager\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use UrlManager\Services\UserService;
use UrlManager\Services\UrlService;
use UrlManager\Services\TransitionService;

class TransitionController extends AbstractController
{
    protected $transitionService;
    protected $urlService;



    public function __construct(
          UserService $userService,
          UrlService $urlService,
          TransitionService $transitionService
    ) {
        parent::__construct($userService);
        $this->urlService = $urlService;
        $this->transitionService = $transitionService;
    }



    public function fixTransition($hash)
    {
        $shortenUrl = $this->urlService->getShortenUrlByHash($hash);

        if ($shortenUrl == null) {
            return new JsonResponse(
                [],
                Response::NOT_FOUND
            );
        }

        $this->transitionService->fixTransition($shortenUrl->getId());

        return new JsonResponse (
            ['url' => $shortenUrl->getSourceUrl()],
            RedirectResponse::HTTP_TEMPORARY_REDIRECT
        );
    }

    public function getTopReferer(Request $request, $id)
    {
        try {

            $user = $this->getUserByAuthorization($request);
            if ($user === false) {
                return $this->createUnauthorizedResponse();
            }

            $referers = $this->transitionService->getTopReferer($id);

            if ($referers == null) {
                return new JsonResponse(
                    [],
                    Response::HTTP_NO_CONTENT
                );
            }

        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        }

        return new JsonResponse (
            $referers
        );
    }

    public function getCountTransitionsForPeriodGroupByDays(Request $request, $id)
    {
        try {

            $this->setDate($request);

            $user = $this->getUserByAuthorization($request);
            if ($user === false) {
                return $this->createUnauthorizedResponse();
            }

            $countTransitionsGroupByDays = $this->transitionService->getCountTransitionsPeriod(
                $request->query->get('from_date'),
                $request->query->get('to_date'),
                $id,
                '%Y-%m-%d'
            );

            if ($countTransitionsGroupByDays == null) {
                return new JsonResponse(
                    [],
                    Response::HTTP_NO_CONTENT
                );
            }

        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        } catch (\Exception $e) {
            return $this->createErrorResponse('Date is wrong');
        }

        return new JsonResponse (
            $countTransitionsGroupByDays
        );
    }

    public function getCountTransitionsForPeriodGroupByHours(Request $request, $id)
    {
        try {

            $this->setDate($request);

            $user = $this->getUserByAuthorization($request);
            if ($user === false) {
                return $this->createUnauthorizedResponse();
            }

            $countTransitionsGroupByDays = $this->transitionService->getCountTransitionsPeriod(
                $request->query->get('from_date'),
                $request->query->get('to_date'),
                $id,
                '%Y-%m-%d %h'
            );

            if ($countTransitionsGroupByDays == null) {
                return new JsonResponse(
                    [],
                    Response::HTTP_NO_CONTENT
                );
            }

        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        } catch (\Exception $e) {
            return $this->createErrorResponse('Date is wrong');
        }

        return new JsonResponse (
            $countTransitionsGroupByDays
        );
    }

    public function getCountTransitionsForPeriodGroupByMins(Request $request, $id)
    {
        try {

            $this->setDate($request);

            $user = $this->getUserByAuthorization($request);
            if ($user === false) {
                return $this->createUnauthorizedResponse();
            }

            $countTransitionsGroupByDays = $this->transitionService->getCountTransitionsPeriod(
                $request->query->get('from_date'),
                $request->query->get('to_date'),
                $id,
                '%Y-%m-%d %h %i'
            );

            if ($countTransitionsGroupByDays == null) {
                return new JsonResponse(
                    [],
                    Response::HTTP_NO_CONTENT
                );
            }

        } catch (\InvalidArgumentException $e) {
            return $this->createErrorResponse($e->getMessage());
        } catch (\Exception $e) {
            return $this->createErrorResponse('Date is wrong');
        }

        return new JsonResponse (
            $countTransitionsGroupByDays
        );
    }



    protected function setDate($request)
    {
        $fromDate = $request->query->get('from_date');
        $toDate = $request->query->get('to_date');

        if (($toDate !== null && strlen($toDate) != 10) ||
            ($froDate !== null && strlen($fromDate) != 10)) throw new \Exception();

        if ($toDate === null) {
            $toDate = \DateTime::createFromFormat(
                'Y-m-d',
                (new \DateTime('now',new \DateTimeZone('UTC')))->format('Y-m-d')
            );
        } else {
            $toDate = \DateTime::createFromFormat('Y-m-d',$toDate);
        }

        if ($fromDate === null) {
            $fromDate = clone $toDate;
            $fromDate->modify('-7 days');
        } else {
            $fromDate = \DateTime::createFromFormat('Y-m-d',$fromDate);
        }

        if ($fromDate >= $toDate) throw new \Exception();

        $request->query->set('from_date', $fromDate);
        $request->query->set('to_date', $toDate);


    }
}
