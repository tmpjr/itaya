<?php 

namespace Itaya;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use InvalidArgumentException;

class UserController
{
	public function indexAction(Request $request, Application $app)
	{
		$id = intval($request->get('id'));

		if ($id < 0) {
			throw new InvalidArgumentException("ID Must be an integer");
		}

		$app['monolog']->addDebug("ID: {$id}");
		
		$user = $app['sentry']->getUserProvider()->findById($id);
		//$sql = "SELECT * FROM genes";
		//$gene = $app['db']->fetchAssoc($sql);
		//$app['monolog']->addDebug(print_r($gene,true));
		return new JsonResponse($user);
	}

	public function createAction()
	{

	}
}