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
		$sql = "SELECT * FROM gene";
		$gene = $this->app['db']->fetchAssoc($sql);
		return new JsonResponse($gene);
	}
}