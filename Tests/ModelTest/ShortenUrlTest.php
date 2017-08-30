<?php
namespace UrlManager\Tests\ModelTest;

use UrlManager\Models\User;
use UrlManager\Models\ShortenUrl;
use PHPUnit\Framework\TestCase;

class ShortenUrlTest extends TestCase
{
    public function dataTestConstruct()
    {
        $user = new User('name','password','name@mail.ru');
        return [
          ['','',$user,true],
          ['aaaaaaaaaaaaaaaa','',$user,true],
          ['aaaaaaaaaaaaaaaa','https://google.com',$user,false],
        ];
    }

    /**
      *@dataProvider dataTestConstruct
    */

    public function testConstruct($hash, $sourceUrl, $user, $isException)
    {
        if ($isException)
            $this->expectException(\Exception::class);

        $shortenUrl = new ShortenUrl($hash, $sourceUrl, $user);

        if (!$isException)
            $this->assertArraySubset(
                [
                    $shortenUrl->getHash(),
                    $shortenUrl->getSourceUrl(),
                    $shortenUrl->getUser()->getEmail()
                ],
                [
                    $hash,
                    $sourceUrl,
                    $user->getEmail()
                ]
            );
    }
}
