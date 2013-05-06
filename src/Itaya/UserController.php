<?php 

namespace Itaya;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use InvalidArgumentException;

class UserController
{
	private $app;

	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	public function indexAction()
	{
		$user = $this->app['sentry']->getUserProvider()->findById(1);
		$sql = "SELECT * FROM genes";
		$gene = $this->app['db']->fetchAssoc($sql);
		$this->app['monolog']->addDebug(print_r($gene,true));
		return new JsonResponse($user);
	}

	public function createAction()
	{

	}
}