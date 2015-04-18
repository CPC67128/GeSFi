<?php
class UsersHandler extends Handler
{
	function GetRolesList()
	{
		$types = array
		(
				0 => 'Lecteur',
				1 => 'Utilisateur',
				2 => 'Administrateur'
		);
	
		return $types;
	}

	function GetUser($id)
	{
		$newUser = new User();

		$db = new DB();
	
		$query = "select *
			from {TABLEPREFIX}user
			where user_id = '".$id."'";
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
		$query = "select count(*) as total
			from {TABLEPREFIX}user
			where user_id = '".$userId."'";
		$row = $db->SelectRow($query);

		return $row['total'] > 0;
	}

	function GetUserByUserName($userName)
	{
		$newUser = null;
	
		$db = new DB();
	
		$query = sprintf("select * from {TABLEPREFIX}user where lower(user_name) = '%s'",
				strtolower($userName));
	
		$result = $db->Select($query);
		if ($row = $result->fetch())
		{
			$newUser = new User();
			$newUser->hydrate($row);
		}
	
		return $newUser;
	}
	

	function _____________GetUserByEmail($email)
	{
		$newUser = null;
	
		$db = new DB();

		$query = sprintf("select * from {TABLEPREFIX}user where lower(email) = '%s'",
				$email);

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
	
		$query = "select *
			from {TABLEPREFIX}user
			where user_id = '{USERID}'";
		$result = $db->Select($query);
		if ($row = $result->fetch())
		{
			$newUser->hydrate($row);
		}
	
		return $newUser;
	}

	function InsertUser($userName, $name, $email, $passwordHash, $role)
	{
		$db = new DB();

		$query = sprintf("insert into {TABLEPREFIX}user (user_name, name, email, password, role, subscription_date, user_id)
				values ('%s', '%s', '%s', '%s', %s, now(), uuid())",
				$db->ConvertStringForSqlInjection($userName),
				$db->ConvertStringForSqlInjection($name),
				$db->ConvertStringForSqlInjection($email),
				$db->ConvertStringForSqlInjection($passwordHash),
				$role);
		//throw new Exception($query);
	
		$result = $db->Execute($query);
	
		return $result;
	}

	function UpdateUser($userId, $name, $email)
	{
		$db = new DB();
	
		$query = sprintf("update {TABLEPREFIX}user set name = '%s', email = '%s' where user_id = '%s'",
				$name,
				$email,
				$userId);
	
		$result = $db->Execute($query);
	
		return $result;
	}

	function UpdateDuo($userId, $partnerUserId)
	{
		$db = new DB();
		
		if ($partnerUserId == '')
			throw new Exception("Merci de sélectionner un partenaire");
		
		$query = sprintf("select duo_id from {TABLEPREFIX}user where user_id = '%s'",
				$userId);
		$row = $db->SelectRow($query);
		$duoId = $row['duo_id'];

		$query = sprintf("select duo_id from {TABLEPREFIX}user where user_id = '%s'",
				$partnerUserId);
		$row = $db->SelectRow($query);
		$partnerDuoId = $row['duo_id'];

		if ($partnerDuoId != '' && $partnerDuoId != $duoId)
			throw new Exception("Votre partenaire est déjà déclaré(e) comme étant en couple avec quelqu'un d'autre");

		if ($duoId != '' && $partnerDuoId != $duoId)
			throw new Exception("Vous êtes déjà en couple... il faut déclarer une séparation d'abord");

		$uuid = $db->GenerateUUID();

		$query = sprintf("update {TABLEPREFIX}user set duo_id = '%s' where user_id in ('%s', '%s')",
				$uuid,
				$userId,
				$partnerUserId);
		$result = $db->Execute($query);

		return $result;
	}

	/*
	function IsPasswordCorrect($Email, $Hashed_password)
	{
		$db = new DB();

		$are_passwords_matching = false;
	
		$escaped_email = $db->ConvertStringForSqlInjection($Email);
	
		$query = sprintf("select password from {TABLEPREFIX}user where lower(email) = '%s'",
				strtolower($escaped_email));

		$row = $db->SelectRow($query);
	
		if (isset($row["password"]))
		{
			if ($row["password"] == $Hashed_password)
				$are_passwords_matching = true;
		}

		return $are_passwords_matching;
	}
	*/

	function RecordUserConnection()
	{
		$db = new DB();
	
		$escaped_browser = $db->ConvertStringForSqlInjection($_SERVER['HTTP_USER_AGENT']);
	
		$query = sprintf("insert into {TABLEPREFIX}user_connection (user_id, connection_date_time, ip_address, browser) values('{USERID}', now(), '%s', '%s')",
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
		$_SESSION['read_only'] = $user->get('readOnly');
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