<?php 

namespace Itaya;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use InvalidArgumentException;
use Cartalyst\Sentry\Users;

class UserController
{
	public function createAction(Request $request, Application $app)
	{
		$email = trim($request->get('email'));
		$password = $request->get('password');

		$userProvider = $user = $app['sentry']->getUserProvider();

		try {
			$user = $userProvider->create(array(
				'email' 	=> $email,
				'password' 	=> $password
			));
		} catch (UserExistsException $e) {
			print_r($e);
			return new JsonResponse($e);
		}
		

		//$app['monolog']->addDebug(print_r($gene,true));
		return new JsonResponse($user->toArray());
	}
}