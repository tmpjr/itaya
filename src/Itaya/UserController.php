<?php 

namespace Itaya;

use Symfony\Component\HttpFoundation\JsonResponse;

class UserController
{
	private $app;

	public function __construct($app)
	{
		$this->app = $app;
	}

	public function indexAction()
	{
		$sql = "SELECT * FROM genes";
		$gene = $this->app['db']->fetchAssoc($sql);
		$this->app['monolog']->addDebug(print_r($gene,true));
		return new JsonResponse($gene);
	}
}