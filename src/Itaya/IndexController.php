<?php

namespace Itaya;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use InvalidArgumentException;

class IndexController
{
	public function indexAction(Request $request, Application $app)
	{
		return $app['twig']->render('index.html');
	}

	public function loginAction(Request $request, Application $app)
	{
		return $app['twig']->render('login.html', array(
	        'error'         => $app['security.last_error']($request),
	        'last_username' => $app['session']->get('_security.last_username'),
	    ));
	}

	public function logoutAction(Request $request, Application $app)
	{
		$app['session']->clear();
		return $app->redirect($app['url_generator']->generate('login'));
	}
}