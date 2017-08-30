<?php
namespace UrlManager\Services;

use UrlManager\Repositories\UrlRepository;
use UrlManager\Models\ShortenUrl;
use UrlManager\Models\User;


class UrlService
{
    private $urlRepository;



    public function __construct(UrlRepository $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }




    public function createUserShortenUrl($sourceUrl, User $user)
    {
        $shortenUrl = new ShortenUrl(
            $sourceUrl,
            $user
        );

        $shortenUrl = $this->urlRepository->saveUserShortenUrl($shortenUrl);

        return $shortenUrl;
    }

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
    }
}
