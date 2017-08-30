<?php
namespace UrlManager\Services;

use UrlManager\Models\User;
use UrlManager\Repositories\TransitionRepository;

class TransitionService
{
    private $transitionRepository;



    public function __construct(TransitionRepository $transitionRepository)
    {
         $this->transitionRepository = $transitionRepository;
    }




    public function getCountTransitionsUserShortenUrl($id)
    {
        return $this->transitionRepository->getCountTransitionsUserShortenUrl($id);
    }

    public function getCountTransitionsPeriod(
        $dateFrom,
        $dateTo,
        $id,
        $dateFormat)
    {
        return $this->transitionRepository->getCountTransitionsPeriod(
          $dateFrom,
          $dateTo,
          $id,
          $dateFormat);
    }

    public function getTopReferer($id)
    {
        return $this->transitionRepository->getTopReferer($id);
    }

    public function fixTransition($id)
    {
        $this->transitionRepository->fixTransition($id);
    }
}
