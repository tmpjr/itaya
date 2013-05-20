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

		$roles = 'ROLE_ADMIN';
		$hash = password_hash($password, PASSWORD_BCRYPT);

		$stmt = $app['db']->prepare("INSERT INTO user 
			(username,pwd_hash,roles,activated) 
			VALUES (:username,:pwd_hash,:roles,:activated)");
		$stmt->bindValue(':username', $username);
		$stmt->bindValue(':pwd_hash', $hash);
		$stmt->bindValue(':roles', $roles);
		$stmt->bindValue(':activated', $activated);
		$stmt->execute();

		return new JsonResponse(array(
			'success' => true,
			'user_id' => $app['db']->lastInsertId()
		));
	}

	public function updateAction(Request $request, Application $app)
	{
		$id = intval($request->get('id'));
		$username = trim($request->get('username'));
		$password = $request->get('password');
		$activated = intval($request->get('activated'));

		$roles = 'ROLE_ADMIN';
		$hash = password_hash($password, PASSWORD_BCRYPT);

		$sql = "UPDATE 
					user 
				SET 
					updated_on = NOW(),
					username = :username,
					pwd_hash = :pwd_hash, 
					roles = :roles, 
					activated = :activated 
				WHERE 
					id = :id";
		$stmt = $app['db']->prepare($sql);
		$stmt->bindValue(':username', $username);
		$stmt->bindValue(':pwd_hash', $hash);
		$stmt->bindValue(':roles', $roles);
		$stmt->bindValue(':activated', $activated);
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		return new JsonResponse(array(
			'success' => true,
			'user_id' => $app['db']->lastInsertId()
		));
	}

	public function loginAction(Request $request, Application $app)
	{
		$username = trim($request->get('username'));
		$password = $request->get('password');
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$status = false;

		$sql = "SELECT * FROM user WHERE username = :user";
		$stmt = $app['db']->prepare($sql);
		$stmt->bindValue(':user', $username);
		$stmt->execute();
		$user = $stmt->fetch();

		if ($user !== false) {
			if (password_verify($password, $hash)) {
				$status = true;
			}
		}

		return new JsonResponse(array(
			'status' => $status,
			'user' => $user
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