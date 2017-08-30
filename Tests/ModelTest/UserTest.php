<?php
namespace UrlManager\Tests\ModelTest;

use PHPUnit\Framework\TestCase;
use UrlManager\Models\User;

class UserTest extends TestCase
{
    public function dataTestConstruct()
    {
        return [
          ['','','',true],
          ['name','','',true],
          ['name','password','',true],
          ['name','password','name@mail.ru',false],
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
                    $password,
                    $email
                ]
            );
    }
}
