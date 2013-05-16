<?php 

namespace Itaya;

use Silex\WebTestCase;

class UserControllerTest extends WebTestCase
{
	public function createApplication()
	{
		$app = require __DIR__ . '/../../app/app.php';	
		$app['debug'] = true;
		$app['exception_handler']->disable();
		$app['session.test'] = true;
		return $app;
	}
	
	public function testIsUserFetchValidJson()
	{
		$client = $this->createClient();
		$client->request('GET', '/user/2');
		//$response = $client->getResponse();
		//$data = json_decode($response->getContent());
		//$this->assertJson($data);
		//$this->assertTrue(true, true);
	}
}
