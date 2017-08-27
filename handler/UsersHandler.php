<?php
class UsersHandler extends Handler
{
	function GetRolesList()
	{
		$types = array
		(
				0 => 'Administrateur',
				1 => 'Utilisateur'
		);
	
		return $types;
	}

	function GetUser($id)
	{
		$newUser = new User();

		$db = new DB();

		$query = "select * from {TABLEPREFIX}user where user_id = '".$id."'";

		$result = $db->Select($query);
		if ($row = $result->fetch())
		{
			$newUser->hydrate($row);
		}
	
		return $newUser;
	}
	
	function IsUserIdExisting($userId)
	{
		$db = new DB();
		$query = "select count(*) as total from {TABLEPREFIX}user where user_id = '".$userId."'";
		$row = $db->SelectRow($query);

		return $row['total'] > 0;
	}

	function GetUserByUserName($name)
	{
		$newUser = null;
	
		$db = new DB();
	
		$query = sprintf("select * from {TABLEPREFIX}user where lower(name) = '%s'",
				strtolower($name));
	
		$result = $db->Select($query);
		if ($row = $result->fetch())
		{
			$newUser = new User();
			$newUser->hydrate($row);
		}

		return $newUser;
	}

	function GetCurrentUser()
	{
		$newUser = new User();

		$db = new DB();

		$query = "select * from {TABLEPREFIX}user where user_id = '{USERID}'";
		$result = $db->Select($query);
		if ($row = $result->fetch())
		{
			$newUser->hydrate($row);
		}
	
		return $newUser;
	}

	function UpdateUser($userId, $name, $email, $role)
	{
		$db = new DB();
	
		$query = sprintf("update {TABLEPREFIX}user set name=%s, email=%s, role=%s where user_id = '%s'",
				$db->ConvertStringForSqlInjection($name),
				$db->ConvertStringForSqlInjection($email),
				$role,
				$userId);
	
		$result = $db->Execute($query);
	
		return $result;
	}

	function UpdateUserPassword($userId, $passwordHash)
	{
		$db = new DB();
	
		$query = sprintf("update {TABLEPREFIX}user set password='%s' where user_id='%s'",
				$db->ConvertStringForSqlInjection($passwordHash),
				$userId);
	
		$result = $db->Execute($query);
	
		return $result;
	}

	function UpdateUserActive($userId, $active)
	{
		$db = new DB();
	
		$query = sprintf("update {TABLEPREFIX}user set active=%s where user_id='%s'",
				$active,
				$userId);
	
		$result = $db->Execute($query);
	
		return $result;
	}

	function RecordUserConnection()
	{
		$db = new DB();
	
		$escaped_browser = $db->ConvertStringForSqlInjection($_SERVER['HTTP_USER_AGENT']);
	
		$query = sprintf("insert into {TABLEPREFIX}user_connection (user_id, connection_date_time, ip_address, browser) values('{USERID}', now(), '%s', %s)",
				$_SERVER['REMOTE_ADDR'],
				$escaped_browser);

		$result = $db->Execute($query);
	
		return true;
	
	}

	function StartSession()
	{
		session_start();
	}

	function SetSessionUser($userId)
	{
		$user = $this->GetUser($userId);
		$_SESSION['email'] = $user->get('email');
		$_SESSION['user_id'] = $user->get('userId');
		$_SESSION['full_name'] = $user->get('name');
		$_SESSION['user_name'] = $user->get('userName');
	}

	function GetSessionUser()
	{
		$user = $this->GetUser($_SESSION['user_id']);
		return $user;
	}
	
	function IsSessionUserSet()
	{
		if (!empty($_SESSION['user_id']))
			return $this->IsUserIdExisting($_SESSION['user_id']);
		return false;
	}

	function UnsetSessionUser()
	{
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
			);
		}

		session_unset();
		session_destroy();

		session_commit();
	}

	function GetAllUsers()
	{
		$users = array();
	
		$db = new DB();
	
		$query = 'select * from {TABLEPREFIX}user';
		$result = $db->Select($query);
		while ($row = $result->fetch())
		{
			$newUser = new User();
			$newUser->hydrate($row);
			array_push($users, $newUser);
		}
	
		return $users;
	}
}