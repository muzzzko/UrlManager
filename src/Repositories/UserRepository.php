<?php
namespace UrlManager\Repositories;

use UrlManager\Models\User;
use UrlManager\Models\Password;

class UserRepository extends AbstractRepository
{
    public function saveUser(User $user)
    {
        $this->dbConnection->executeQuery(
            'insert into Users(name, password, email) values(?, ?, ?)',
            [
                $user->getName(),
                $user->getPassword(),
                $user->getEmail()
            ]
        );

        $user = new User(
            $user->getName(),
            new Password($user->getPassword(), false),
            $user->getEmail(),
            $this->dbConnection->lastInsertId()
        );

        return $user;
    }

    public function getUserByEmail($email)
    {
        $row = $this->dbConnection->fetchArray(
            'select name,password,email,id from Users where email = ?',
            [$email]
        );

        return $row[0] !== null ?
            new User($row[0], new Password($row[1], false), $row[2], $row[3]) :
            null;
    }
}
