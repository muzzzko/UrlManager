<?php
namespace UrlManager\Repositories;

use Doctrine\DBAL\Connection;

class AbstractRepository
{
    protected $dbConnection;


    public function __construct(Connection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }
}
