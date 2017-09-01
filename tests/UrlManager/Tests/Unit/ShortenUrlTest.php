<?php
namespace UrlManager\tests\ModelTest;

use UrlManager\Models\User;
use UrlManager\Models\ShortenUrl;
use PHPUnit\Framework\TestCase;
use UrlManager\Models\Password;

class ShortenUrlTest extends TestCase
{
    public function dataTestConstruct()
    {
        $user = new User('name',new Password('password'),'name@mail.ru');
        return [
          ['',$user,true],
          ['https://google.com',$user,false]
        ];
    }

    /**
      *@dataProvider dataTestConstruct
    */

    public function testConstruct($sourceUrl, $user, $isException)
    {
        if ($isException)
            $this->expectException(\Exception::class);

        $shortenUrl = new ShortenUrl($sourceUrl, $user);

        if (!$isException)
            $this->assertArraySubset(
                [
                    $shortenUrl->getSourceUrl(),
                    $shortenUrl->getUser()->getEmail()
                ],
                [
                    $sourceUrl,
                    $user->getEmail()
                ]
            );
    }
}
