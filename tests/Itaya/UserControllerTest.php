<?php 

namespace Itaya;

use Silex\WebTestCase;

class UserControllerTest extends WebTestCase
{
	public function createApplication()
	{
		$app = require __DIR__ . '/../../web/app.php';	
		$app['debug'] = true;
    	$app['exception_handler']->disable();

    	return $app;
	}
	
	public function testUserFetch()
	{
		$client = $this->createClient();
    	$crawler = $client->request('GET', '/user/1');
    	$this->assertTrue($client->getResponse()->isOk());
	}
}