<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__ . '/../resources/config/dev.php';

$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['db.options']);

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['user.controller'] = $app->share(function() use ($app) {
    return new Itaya\UserController($app);
});

$app->get('/user', "user.controller:indexAction");


$app->run();