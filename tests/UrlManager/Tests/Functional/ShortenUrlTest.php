<?php
namespace UrlManager\Functional\Tests;

use Doctrune\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Silex\WebTestCase;
use Silex\Application;
use UrlManager\Models\Password;

class ShortenUrlTest extends WebTestCase
{
    public function createApplication()
    {
        $app = new Application();

        require __DIR__ . '/../../../../src/app.php';

        return $app;
    }

    public function testCreateUserShortenUrl()
    {
        $dbConnection = $this->app['db'];

        $dbConnection->executeQuery(
            'delete from Users where email in (\'test2@gmail.com\')'
        );

        $password = new Password('password');

        $dbConnection->executeQuery(
            'insert into Users(email, name, password)
            values(\'test2@gmail.com\',\'name\',\''.$password->getPassword().'\')'
        );

        $client = $this->createClient();

        $client->request(
            'POST',
            '/api/v1/users/me/shorten_urls',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'PHP_AUTH_USER' => 'test2@gmail.com',
                'PHP_AUTH_PW' => 'password'
            ],
            '{"url":"http://google.com"}'
        );

        $this->assertEquals(
            Response::HTTP_CREATED,
            $client->getResponse()->getStatusCode()
        );

        return json_decode($client->getResponse()->getContent(),true)['id'];

    }

    /**
      *@depends testCreateUserShortenUrl
    */
    public function testGetUserShortenUrl($id)
    {
        $client = $this->createClient();

        $client->request(
            'GET',
            '/api/v1/users/me/shorten_urls/'.$id,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'PHP_AUTH_USER' => 'test2@gmail.com',
                'PHP_AUTH_PW' => 'password'
            ]
        );

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
    }

    /**
      *@depends testCreateUserShortenUrl
    */
    public function testGetAllUserShortenUrls()
    {
        $client = $this->createClient();

        $client->request(
            'GET',
            '/api/v1/users/me/shorten_urls',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'PHP_AUTH_USER' => 'test2@gmail.com',
                'PHP_AUTH_PW' => 'password'
            ]
        );

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
    }

    /**
      *@depends testCreateUserShortenUrl
    */
    public function testTransition($id)
    {
        $dbConnection = $this->app['db'];

        $row = $dbConnection->fetchAssoc(
            'select * from Urls where id = '.$id
        );

        $client = $this->createClient();

        $client->request(
            'GET',
            '/api/v1/shorten_urls/'.$row['hash'],
            [],
            [],
            [
                'HTTP_REFERER' => "http://vk.com"
            ]
        );

         $this->assertEquals(
             Response::HTTP_TEMPORARY_REDIRECT,
             $client->getResponse()->getStatusCode()
         );
    }


    /**
      *@depends testCreateUserShortenUrl
    */
    public function testGetTopReferers($id)
    {
        $client = $this->createClient();

        $client->request(
            'GET',
            '/api/v1/users/me/shorten_urls/'.$id.'/referers',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'PHP_AUTH_USER' => 'test2@gmail.com',
                'PHP_AUTH_PW' => 'password'
            ]
        );

        $this->assertEquals(
            1,
            count(json_decode($client->getResponse()->getContent()))
        );
    }

    /**
      *@depends testCreateUserShortenUrl
    */
    public function testGetCountTransitionsForPeriod($id)
    {
        $client = $this->createClient();

        $client->request(
            'GET',
            '/api/v1/users/me/shorten_urls/'.$id.'/days',
            [
                'from_date' => '2016-08-10',
                'to_date' => '2017-09-01'
            ],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'PHP_AUTH_USER' => 'test2@gmail.com',
                'PHP_AUTH_PW' => 'password'
            ]
        );

        $this->assertEquals(
            1,
            count(json_decode($client->getResponse()->getContent()))
        );

        $client->request(
            'GET',
            '/api/v1/users/me/shorten_urls/'.$id.'/hours',
            [
                'from_date' => '2016-08-10',
                'to_date' => '2017-09-01'
            ],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'PHP_AUTH_USER' => 'test2@gmail.com',
                'PHP_AUTH_PW' => 'password'
            ]
        );

        $this->assertEquals(
            1,
            count(json_decode($client->getResponse()->getContent()))
        );

        $client->request(
            'GET',
            '/api/v1/users/me/shorten_urls/'.$id.'/mins',
            [
                'from_date' => '2016-08-10',
                'to_date' => '2017-09-01'
            ],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'PHP_AUTH_USER' => 'test2@gmail.com',
                'PHP_AUTH_PW' => 'password'
            ]
        );

        $this->assertEquals(
            1,
            count(json_decode($client->getResponse()->getContent()))
        );
    }

    /**
      *@depends testCreateUserShortenUrl
    */
    public function testDeleteUserShortenUrl($id)
    {
        $client = $this->createClient();

        $client->request(
            'DELETE',
            '/api/v1/users/me/shorten_urls/'.$id,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'PHP_AUTH_USER' => 'test2@gmail.com',
                'PHP_AUTH_PW' => 'password'
            ]
        );

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
    }

}
