<?php
namespace UrlManager\Services;

<<<<<<< HEAD
use UrlManager\Models\User;
=======
>>>>>>> bbf0a2dd6575f750ea739db6c81e11843ddae446
use UrlManager\Repositories\TransitionRepository;

class TransitionService
{
    private $transitionRepository;



    public function __construct(TransitionRepository $transitionRepository)
    {
         $this->transitionRepository = $transitionRepository;
    }



<<<<<<< HEAD
    public function getCountTransitionsUserShortenUrl($id)
    {
        return $this->transitionRepository->getCountTransitionsUserShortenUrl($id);
    }

    public function getCountTransitionsPeriod(
        $dateFrom,
        $dateTo,
        $id,
        $dateFormat)
=======
    public function getCountTransitionsPeriod(
        $dateFrom,
        $dateTo,
        $hash,
        $dateFromat)
>>>>>>> bbf0a2dd6575f750ea739db6c81e11843ddae446
    {
        return $this->transitionRepository->getCountTransitionsPeriod(
          $dateFrom,
          $dateTo,
<<<<<<< HEAD
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
=======
          $hash,
          $dateFormat);
    }

    public function getTopReferer()
    {
        return $this->TransitionRepository->getTopReferer();
    }

    public function fixTransition($hash)
    {
        $this->TransitionRepository->fixTransition($hash);
>>>>>>> bbf0a2dd6575f750ea739db6c81e11843ddae446
    }
}
