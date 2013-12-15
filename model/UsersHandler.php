<?php
class UsersHandler
{
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
	
	function GetUserByEmail($email)
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

	function IsPasswordCorrect($Email, $Hashed_password)
	{
		$db = new DB();

		$are_passwords_matching = false;
	
		$escaped_email = String2StringForSprintfQueryBuilder($Email);
	
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

	function RecordUserConnection($User_id, $Ip, $Browser)
	{
		$db = new DB();
	
		$escaped_browser = String2StringForSprintfQueryBuilder($Browser);
	
		$query = sprintf("insert into {TABLEPREFIX}user_connection (user_id, connection_date_time, ip_address, browser) values('%s', now(), '%s', '%s')",
				$User_id,
				$Ip,
				$escaped_browser);

		$result = $db->Execute($query);
	
		return true;
	
	}
}