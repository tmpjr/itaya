<?php 

namespace Itaya;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use InvalidArgumentException;
//use Cartalyst\Sentry\Users;

class UserController
{
	public function createAction(Request $request, Application $app)
	{
		$email = trim($request->get('email'));
		$password = $request->get('password');

		$userProvider = $app['sentry']->getUserProvider();

		try {
			$user = $userProvider->create(array(
				'email' 	=> $email,
				'password' 	=> $password
			));
		} catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
		    return new JsonResponse($e);
		} catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
		    return new JsonResponse($e);
		} catch (Cartalyst\Sentry\Users\UserExistsException $e) {
		    return new JsonResponse($e);
		} catch (Cartalyst\Sentry\Users\GroupNotFoundException $e) {
		    return new JsonResponse($e);
		}

		//$app['monolog']->addDebug(print_r($gene,true));
		return new JsonResponse($user->toArray());
	}

	public function fetchAction(Request $request, Application $app)
	{
		$id = intval($request->get('id'));

		try {
			$user = $app['sentry']->getUserProvider()->findById($id);
		} catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
			return new JsonResponse($e);
		}
		
		return new JsonResponse($user->toArray());
	}
}