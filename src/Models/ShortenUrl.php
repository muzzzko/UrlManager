<?php
namespace UrlManager\Models;

use UrlManager\Models\User;

class ShortenUrl
{
    protected $shortenUrl;
    protected $user;
    protected $sourceUrl;
    protected $id;
    protected $hash;



    public function __construct($sourceUrl, User $user, $id = null, $hash = null)
    {
        if (!filter_var($sourceUrl,FILTER_VALIDATE_URL))
            throw new \InvalidArgumentException('Url is wrong');

        if ($hash === null)
            $hash = $this->createHash($sourceUrl);

        $this->hash = $hash;
        $this->shortenUrl = "app/v1/shorten_urls/".$hash;
        $this->user = $user;
        $this->sourceUrl = $sourceUrl;
        $this->id = $id;
    }



    public function getShortenUrl()
    {
        return $this->shortenUrl;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getSourceUrl()
    {
        return $this->sourceUrl;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHash()
    {
        return $this->hash;
    }




    protected function createHash($sourceUrl)
    {
        return hash('md5',$sourceUrl . mt_rand());
    }
}
