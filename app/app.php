<?php

require_once __DIR__ . '/bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;

$app = new Silex\Application();

require __DIR__ . '/../resources/config/dev.php';

// Register database handle
$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['db.options']);

// Setup sessions
$app->register(new Silex\Provider\SessionServiceProvider());

// Register view
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

// Register logging
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../resources/log/app.log',
));

$app->register(new UrlGeneratorServiceProvider());

// General Service Provder for Controllers
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

// Security definition.
$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        // Login URL is open to everybody.
        'login' => array(
            'pattern' => '^/login$',
            'anonymous' => true,
        ),
        // Any other URL requires auth.
        'index' => array(
            'pattern' => '^.*$',
            'form'      => array(
                'login_path'         => '/login',
                'check_path'        => '/login_check',
                'username_parameter' => 'username',
                'password_parameter' => 'password',
            ),
            'anonymous' => false,
            'logout'    => array('logout_path' => '/logout'),
            'users'     => $app->share(function() use ($app) {
                return new Itaya\UserProvider($app);
            }),
        ),
    ),
));

// Define a custom encoder for Security/Authentication
$app['security.encoder.digest'] = $app->share(function ($app) {
    // uses the password-compat encryption
    return new BCryptPasswordEncoder(10);
});

// ROUTES
$app['index.controller'] = $app->share(function() use ($app) {
    return new Itaya\IndexController();
});
$app->get('/', "index.controller:indexAction");
$app->get('/index', "index.controller:indexAction");

$app['auth.controller'] = $app->share(function() use ($app) {
    return new Itaya\AuthController();
});
$app->get('/login', "auth.controller:loginAction");
$app->get('/logout', "auth.controller:logoutAction");

$app['user.controller'] = $app->share(function() use ($app) {
    return new Itaya\UserController();
});
$app->get('/user/{id}', "user.controller:fetchAction");
$app->post('/user/create', "user.controller:createAction");
$app->post('/user/update', "user.controller:updateAction");
$app->post('/user/login', "user.controller:loginAction");

// must return $app for unit testing to work
return $app;