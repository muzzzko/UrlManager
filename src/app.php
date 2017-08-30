<?php

use UrlManager\Services\UserService;
use UrlManager\Services\UrlService;
use UrlManager\Services\TransitionService;
use UrlManager\Controllers\UserController;
use UrlManager\Controllers\UrlController;
use UrlManager\Controllers\TransitionController;
use UrlManager\Repositories\UserRepository;
use UrlManager\Repositories\UrlRepository;
use UrlManager\Repositories\TransitionRepository;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$app->register(new DoctrineServiceProvider(),   [
    'db.options' => [
          'driver' => 'pdo_mysql',
<<<<<<< HEAD
          'host' => 'localhost',
          'dbname' => 'UrlManager',
          'user' => 'root',
          'password' => '181295egor',
=======
          'host' => '127.0.0.1',
          'dbname' => 'UrlManager',
          'user' => 'root',
          'password' => 'root',
>>>>>>> bbf0a2dd6575f750ea739db6c81e11843ddae446
          'charset' => 'utf8'
    ]
]);

$app->register(new ServiceControllerServiceProvider());

$app->before(function(Request $request){
    if(0 === strpos($request->headers->get('Content-Type'),'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app['users.controller'] = function ($app) {
    return new UserController($app['users.service']);
};

$app['users.service'] = function ($app) {
    return new UserService($app['users.repository']);
};

$app['users.repository'] = function ($app) {
    return new UserRepository($app['db']);
};

$app['shortenUrls.controller'] = function ($app) {
    return new UrlController(
        $app['users.service'],
        $app['shortenUrls.service'],
        $app['transitions.service']
    );
};

$app['shortenUrls.service'] = function ($app) {
    return new UrlService($app['shortenUrls.repository']);
};

$app['shortenUrls.repository'] = function ($app) {
    return new UrlRepository($app['db']);
};

<<<<<<< HEAD
$app['transitions.controller'] = function ($app) {
    return new TransitionController(
        $app['users.service'],
        $app['shortenUrls.service'],
        $app['transitions.service']
    );
};

=======
>>>>>>> bbf0a2dd6575f750ea739db6c81e11843ddae446
$app['transitions.service'] = function ($app) {
    return new TransitionService($app['transitions.repository']);
};

$app['transitions.repository'] = function ($app) {
    return new TransitionRepository($app['db']);
};

$users = $app['controllers_factory'];

$users->post('/users','users.controller:register');
$users->get('/users/me','users.controller:getUser');

<<<<<<< HEAD
$app->mount('/api/v1', $users);
=======
$app->mount('/api/v1',$users);
>>>>>>> bbf0a2dd6575f750ea739db6c81e11843ddae446

$shortenUrls = $app['controllers_factory'];

$shortenUrls->post('/users/me/shorten_urls','shortenUrls.controller:createUserShortenUrl');
<<<<<<< HEAD
$shortenUrls->get('/users/me/shorten_urls','shortenUrls.controller:getAllUserShortenUrls');
$shortenUrls->get('/users/me/shorten_urls/{id}','shortenUrls.controller:getUserShortenUrl')
            ->assert('id','\d+');
$shortenUrls->delete('/users/me/shorten_urls/{id}','shortenUrls.controller:deleteUserShortenUrl')
            ->assert('id','\d+');

$app->mount('/api/v1', $shortenUrls);

$transitions = $app['controllers_factory'];

$transitions->get('/shorten_urls/{hash}','transitions.controller:fixTransition');
$transitions->get('/users/me/shorten_urls/{id}/referers','transitions.controller:getTopReferer');
$transitions->get(
    '/users/me/shorten_urls/{id}/days',
    'transitions.controller:getCountTransitionsForPeriodGroupByDays'
);
$transitions->get(
    '/users/me/shorten_urls/{id}/hours',
    'transitions.controller:getCountTransitionsForPeriodGroupByHours'
);
$transitions->get(
    '/users/me/shorten_urls/{id}/mins',
    'transitions.controller:getCountTransitionsForPeriodGroupByMins'
);


$app->mount('/api/v1', $transitions);
=======

$app->mount('/api/v1',$shortenUrls);
>>>>>>> bbf0a2dd6575f750ea739db6c81e11843ddae446
