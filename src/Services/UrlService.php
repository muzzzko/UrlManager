<?php
namespace UrlManager\Services;

use UrlManager\Repositories\UrlRepository;
use UrlManager\Models\ShortenUrl;
use UrlManager\Models\User;


class UrlService
{
<<<<<<< HEAD

=======
>>>>>>> bbf0a2dd6575f750ea739db6c81e11843ddae446
    private $urlRepository;



    public function __construct(UrlRepository $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }



<<<<<<< HEAD
    public function createUserShortenUrl($sourceUrl, User $user)
    {
        $shortenUrl = new ShortenUrl(
=======
    public function createUserShortenUrl(User $user, $sourceUrl)
    {
        $shortenUrl = new ShortenUrl(
            md5($sourceUrl . mt_rand(),true),
>>>>>>> bbf0a2dd6575f750ea739db6c81e11843ddae446
            $sourceUrl,
            $user
        );

        $shortenUrl = $this->urlRepository->saveUserShortenUrl($shortenUrl);

        return $shortenUrl;
    }

<<<<<<< HEAD
    public function getAllUserShortenUrls(User $user)
    {
        return $this->urlRepository->getAllUserShortenUrls($user);
    }

    public function getUserShortenUrlById(User $user, $id)
    {
        return $this->urlRepository->getUserShortenUrlById($user, $id);
    }

    public function deleteUserShortenUrl($id)
    {
        return $this->urlRepository->deleteUserShortenUrl($id);
    }

    public function getShortenUrlByHash($hash)
    {
        return $this->urlRepository->getShortenUrlByHash($hash);
=======
    public function getAllUserShortenUrl($email)
    {
        return $this->urlRepository->getAllUserShortenUrl($user);
    }

    public function getUserShortenUrl($email, $id)
    {
        $shortenUrl = $this->urlRepository->getUserShortenUrl($user, $id);

        return $shortenUrl;
    }

    public function deleteUserShortenUrl($email, $id)
    {
        return $this->urlRepository->deleteUserShortenUrl($user, $id);
>>>>>>> bbf0a2dd6575f750ea739db6c81e11843ddae446
    }
}
