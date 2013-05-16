<?php

require_once __DIR__ . '/bootstrap.php';

$app = new Silex\Application();

require __DIR__ . '/../resources/config/dev.php';

// Register database handle
$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['db.options']);

// Register view
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

// Register logging
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../resources/log/app.log',
));

// General Service Provder for Controllers
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['user.controller'] = $app->share(function() use ($app) {
    return new Itaya\UserController();
});
$app->get('/user/{id}', "user.controller:fetchAction");
$app->post('/user/create', "user.controller:createAction");
$app->post('/login', "user.controller:loginAction");

$app->get('/login', function () use ($app) {
    return $app['twig']->render('login.html');
});

// must return $app for unit testing to work
return $app;