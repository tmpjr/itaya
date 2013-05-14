<?php 

namespace Itaya;

use Silex\WebTestCase;

class UserControllerTest extends WebTestCase
{
	public function createApplication()
	{
		return require __DIR__ . '/../../web/app.php';	
		$app['debug'] = true;
    	$app['exception_handler']->disable();

    	return $app;
	}
	
	public function testIsUserFetchValidJson()
	{
		$client = $this->createClient();
    	$client->request('GET', '/user/1');	
    	$response = $client->getResponse();

    	$data = json_decode($response->getContent(), true);

    	$this->assertJson($data);
	}
}