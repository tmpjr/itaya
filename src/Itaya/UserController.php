<?php 

namespace Itaya;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use InvalidArgumentException;

class UserController
{
	public function createAction(Request $request, Application $app)
	{
		$username = trim($request->get('username'));
		$password = $request->get('password');
		$activated = intval($request->get('activated'));

		$permissions = json_encode(array('admin' => 1));
		$hash = password_hash($password, PASSWORD_BCRYPT);

		$stmt = $app['db']->prepare("INSERT INTO user 
			(username,pwd_hash,permissions,activated) 
			VALUES (:username,:pwd_hash,:permissions,:activated)");
		$stmt->bindValue(':username', $username);
		$stmt->bindValue(':pwd_hash', $hash);
		$stmt->bindValue(':permissions', $permissions);
		$stmt->bindValue(':activated', $activated);
		$stmt->execute();

		return new JsonResponse(array(
			'success' => true,
			'user_id' => $app['db']->lastInsertId()
		));
	}

	public function fetchAction(Request $request, Application $app)
	{
		$id = intval($request->get('id'));

		//try {
			$stmt = $app['db']->prepare("SELECT * FROM user WHERE id = :id");
			$stmt->bindValue(':id', $id);
			$stmt->execute();
			$user = $stmt->fetch();
		//} catch (UserNotFoundException $e) {
		//	return new JsonResponse(array('message' => $e->getMessage(), 'status' => 'error'));
		//}

		$response = array(
			'status' 	=> 'success', 
			'data' 		=> $user
		);
		
		return new JsonResponse($response);		
	}
}