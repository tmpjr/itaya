<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__ . '/../resources/config/dev.php';

// Register database handle
$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['db.options']);

// Configure Sentry
$app['sentry.hasher'] = $app->share(function() use ($app){
    return new Cartalyst\Sentry\Hashing\NativeHasher;
});

$app['sentry.provider.user'] = $app->share(function() use ($app){
    return new Cartalyst\Sentry\Users\Eloquent\Provider($app['sentry.hasher']);
});

$app['sentry.provider.group'] = $app->share(function() use ($app){
    return new Cartalyst\Sentry\Groups\Eloquent\Provider;
});

$app['sentry.provider.throttle'] = $app->share(function() use ($app){
    return new Cartalyst\Sentry\Throttling\Eloquent\Provider($app['sentry.provider.user']);
});

$app['sentry.session'] = $app->share(function() use ($app){
    return new Cartalyst\Sentry\Sessions\NativeSession;
});

$app['sentry.cookie'] = $app->share(function() use ($app){
    return new Cartalyst\Sentry\Cookies\NativeCookie(array());
});

$app['sentry'] = $app->share(function() use ($app) {
    $sentry = new Cartalyst\Sentry\Sentry(
        $app['sentry.provider.user'],
        $app['sentry.provider.group'],
        $app['sentry.provider.throttle'],
        $app['sentry.session'],
        $app['sentry.cookie']
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
//$app->get('/user/{id}', "user.controller:createAction");
$app->post('/user/create', "user.controller:createAction");


$app->run();