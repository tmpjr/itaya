<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__ . '/../resources/config/dev.php';

// Register database handle
$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['db.options']);

// Configure Sentry
class_alias('Cartalyst\Sentry\Facades\Native\Sentry', 'Sentry');
Sentry::setupDatabaseResolver(new PDO(
	$app['db.dsn'], 
	$app['db.options']['user'], 
	$app['db.options']['password']
));

$user = Sentry::getUserProvider()->create(array(
    'email'    => 'tploskinajr@gmail.com',
    'password' => 'test123',
    'permissions' => array(
        'test'  => 1,
        'other' => -1,
        'admin' => 1
    )
));

// Register logging
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../resources/log/app.log',
));

// General Service Provder for Controllers
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['user.controller'] = $app->share(function() use ($app) {
    return new Itaya\UserController($app);
});

$app->get('/user', "user.controller:indexAction");


$app->run();