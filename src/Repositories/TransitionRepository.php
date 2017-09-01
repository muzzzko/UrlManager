<?php
namespace UrlManager\Repositories;

use UrlManager\Repositories\AbstractRepository;

class TransitionRepository extends AbstractRepository
{
    public function fixTransition($id, $referer)
    {
        $this->dbConnection->executeQuery(
            'insert into Transitions(urlId, dateTransition, referer) values(?, ?, ?)',
            [
                $id,
                new \Datetime('now', new \DateTimeZone('UTC')),
                $referer
            ],
            [
                \PDO::PARAM_INT,
                'datetime',
                \PDO::PARAM_STR
            ]
        );
    }

    public function getCountTransitionsUserShortenUrl($id)
    {
        $row = $this->dbConnection->fetchAssoc(
            'select count(*) as count from Transitions where urlId = ?',
            [$id]
        );

        return $row['count'];
    }

    public function getCountTransitionsPeriod(
        $fromDate,
        $toDate,
        $id,
        $dateFormat)
    {
        $rows = $this->dbConnection->fetchAll(
            'select DATE_FORMAT(dateTransition,?) as date, COUNT(*) as count from Transitions
             where urlId = ? and dateTransition >= ? and dateTransition <= ?
             group by DATE_FORMAT(dateTransition,?)',
             [$dateFormat, $id, $fromDate, $toDate, $dateFormat],
             [
                 \PDO::PARAM_STR,
                 \PDO::PARAM_INT,
                 'datetime',
                 'datetime',
                 \PDO::PARAM_STR
             ]
        );
        return $rows;
    }

    public function getTopReferer($id)
    {
        $rows = $this->dbConnection->fetchAll(
            'select referer, count(*) as count from Transitions
            where urlId = ?
            group by referer
            order by count desc
            limit 0,20',
            [$id]
        );

        return $rows;
    }
}
