<?php
namespace UrlManager\Repositories;

use UrlManager\Repositories\AbstractRepository;
use UrlManager\Models\ShortenUrl;
use UrlManager\Models\User;

class UrlRepository extends AbstractRepository
{
    public function saveUserShortenUrl(ShortenUrl $shortenUrl)
    {
        $this->dbConnection->executeQuery(
            'insert into Urls(hash, sourceUrl, userId) values(?, ?, ?)',
            [
                $shortenUrl->getHash(),
                $shortenUrl->getSourceUrl(),
                $shortenUrl->getUser()->getId()
            ]
        );

        $shortenUrl = new ShortenUrl(
            $shortenUrl->getSourceUrl(),
            $shortenUrl->getUser(),
            $this->dbConnection->lastinsertId(),
            $shortenUrl->getHash()
        );

        return $shortenUrl;
    }

    public function getAllUserShortenUrls(User $user)
    {
        $rows = $this->dbConnection->fetchAll(
            'select hash, sourceUrl, id from Urls where userId=?',
            [$user->getId()]
        );

        if ($rows == null) return null;

        $shortenUrls = [];
        foreach ($rows as $row) {
            $shortenUrls[] = new ShortenUrl(
                $row['sourceUrl'],
                $user, $row['id'],
                $row['hash']
            );
        }
        return $shortenUrls;
    }

    public function getUserShortenUrlById(User $user, $id)
    {
        $row = $this->dbConnection->fetchAssoc(
            'select sourceUrl, id, hash from Urls where id = ?',
            [$id]
        );

        if ($row == null) return $row;

        return new ShortenUrl($row['sourceUrl'], $user, $row['id'], $row['hash']);
    }

    public function deleteUserShortenUrl($id)
    {
        $this->dbConnection->executeQuery(
            'delete from Urls where id = ?',
            [$id]
        );
    }

    public function getShortenUrlByHash($hash)
    {
        $row = $this->dbConnection->fetchAssoc(
            'select hash, sourceUrl, ur.id, userId, password, name, email from
             Urls as ur join Users as us on ur.userId = us.id where hash = ?',
            [$hash]
        );

        if ($row == null) return $row;

        $user = new User($row['name'], $row['password'], $row['email'], $row['userId']);

        $shortenUrl = new ShortenUrl($row['sourceUrl'], $user, $row['id'], $row['hash']);

        return $shortenUrl;
    }
}
