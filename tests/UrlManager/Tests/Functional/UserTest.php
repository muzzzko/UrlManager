<?php
namespace UrlManager\Functional\Tests;

use Doctrune\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Silex\WebTestCase;
use Silex\Application;
use UrlManager\Models\Password;

class UserTest extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();

        $dbConnection = $this->app['db'];

        $dbConnection->executeQuery(
            'delete from Users where email in (\'test1@gmail.com\',\'test2@gmail.com\')'
        );

        $password = new Password('password');

        $dbConnection->executeQuery(
            'insert into Users(email, name, password)
            values(\'test2@gmail.com\',\'name\',\''.$password->getPassword().'\')'
        );
    }

    public function createApplication()
    {
        $app = new Application();

        require __DIR__ . '/../../../../src/app.php';

        return $app;
    }


    /**
     *@dataProvider dataTestSaveUser
    */
    public function testSaveUser($body, $statusCode)
    {
        $client = $this->createClient();

        $client->request(
            'POST',
            '/api/v1/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $body
        );

        $this->assertEquals(
            $statusCode,
            $client->getResponse()->getStatusCode()
        );
    }

    public function dataTestSaveUser()
    {
        return [
            [
                '{
                      "email":"test@gmail.com",
                      "name":"",
                      "password":"password"
                }',
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                '{
                    "email":"test@gmail.com",
                    "name":"name",
                    "password":"passw"}',
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                '{
                    "email":"test@",
                    "name":"name",
                    "password":"password"
                }',
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                '{
                    "email":"test2@gmail.com",
                    "name":"name",
                    "password":"password"
                }',
                Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            [
                '{
                    "email":"test@gmail.com",
                    "name":"name",
                    "password":"password"
                }',
                Response::HTTP_CREATED
            ],
        ];
    }

    /**
      *@dataProvider dataTestGetUser
    */
    public function testGetUser($headers, $statusCode)
    {
        $client = $this->createClient();

        $client->request(
            'GET',
            '/api/v1/users/me',
            [],
            [],
            $headers
        );

        $this->assertEquals($statusCode, $client->getResponse()->getStatusCode());
    }

    public function dataTestGetUser()
    {
        return [
            [
                [
                    'CONTENT_TYPE' => 'application/json',
                    'PHP_AUTH_USER' => 'test2@gmail.com',
                    'PHP_AUTH_PW' => 'password'
                ],
                Response::HTTP_OK
            ],
            [
                [
                    'CONTENT_TYPE' => 'application/json',
                    'PHP_AUTH_USER' => 'test2@gmail.com',
                    'PHP_AUTH_PW' => 'pas'
                ],
                Response::HTTP_UNAUTHORIZED
            ],
        ];
    }
}
