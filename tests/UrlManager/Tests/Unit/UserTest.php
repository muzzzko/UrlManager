<?php
namespace UrlManager\tests\ModelTest;

use PHPUnit\Framework\TestCase;
use UrlManager\Models\User;
use UrlManager\Models\Password;

class UserTest extends TestCase
{
    public function dataTestConstruct()
    {
        return [
          ['',new Password('Password'),'',true],
          ['name',new Password('password'),'',true],
          ['name',new Password('password'),'name@mail.ru',false],
        ];
    }

    /**
      *@dataProvider dataTestConstruct
    */

    public function testConstruct($name, $password, $email, $isException)
    {
        if ($isException)
            $this->expectException(\Exception::class);

        $user = new User($name, $password, $email);

        if (!$isException)
            $this->assertArraySubset(
                [
                    $user->getName(),
                    $user->getPassword(),
                    $user->getEmail(),
                ],
                [
                    $name,
                    $password->getPassword(),
                    $email
                ]
            );
    }
}
