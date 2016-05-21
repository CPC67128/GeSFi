<?php
class User extends Entity
{
	protected $_userId;
	protected $_userName;
	protected $_email;
	protected $_password;
	protected $_subscriptionDate;
	protected $_role;
	protected $_name;
	protected $_culture;
	protected $_duoId;
	
	// -------------------------------------------------------------------------------------------------------------------

	public function IsNull()
	{
		return !isset($this->_userId);
	}

	function GetPartnerId()
	{
		$db = new DB();

		$query = "select user_id
			from {TABLEPREFIX}user
			where duo_id != ''
			and duo_id = '".$this->_duoId."'
			and user_id != '".$this->_userId."'";
		$row = $db->SelectRow($query);

		if ($row)
		{
			return $row['user_id'];
		}
	
		return '';
	}

	function GetPartnerName()
	{
		$db = new DB();
	
		$query = "select name
		from {TABLEPREFIX}user
		where duo_id != ''
		and duo_id = '".$this->_duoId."'
		and user_id != '".$this->_userId."'";
		$row = $db->SelectRow($query);
	
		if ($row)
		{
			return $row['name'];
		}
	
		return '';
	}
	
	function GetLastConections()
	{
		$db = new DB();

		$query = sprintf("select * from {TABLEPREFIX}user_connection where user_id = '%s' order by connection_date_time desc limit 1,10",
				$this->_userId);
	
		$result = $db->Select($query);

		return $result;
	}
	
	function IsPasswordCorrect($hashedPassword)
	{
		$are_passwords_matching = false;
	
		if ($this->_password == $hashedPassword)
			$are_passwords_matching = true;
	
		return $are_passwords_matching;
	}
}