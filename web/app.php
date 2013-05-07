<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__ . '/../resources/config/dev.php';

// Register database handle
$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['db.options']);

// Configure Sentry
$app['sentry'] = $app->share(function() use ($app) {
    $hasher = new Cartalyst\Sentry\Hashing\NativeHasher;
    $userProvider = new Cartalyst\Sentry\Users\Eloquent\Provider($hasher);
    $groupProvider = new Cartalyst\Sentry\Groups\Eloquent\Provider;
    $throttleProvider = new Cartalyst\Sentry\Throttling\Eloquent\Provider($userProvider);
    $session = new Cartalyst\Sentry\Sessions\NativeSession;
    $cookie = new Cartalyst\Sentry\Cookies\NativeCookie(array());

    $sentry = new Cartalyst\Sentry\Sentry(
        $userProvider,
        $groupProvider,
        $throttleProvider,
        $session,
        $cookie
    );

    Cartalyst\Sentry\Facades\Native\Sentry::setupDatabaseResolver(new PDO(
        $app['db.dsn'], 
        $app['db.options']['user'], 
        $app['db.options']['password']
    ));

    return $sentry;
});

// Register logging
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../resources/log/app.log',
));

// General Service Provder for Controllers
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['user.controller'] = $app->share(function() use ($app) {
    return new Itaya\UserController();
});
$app->get('/user/{id}', "user.controller:indexAction");


$app->run();