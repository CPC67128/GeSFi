<?php
class User extends Entity
{
	protected $_userId;
	protected $_name;
	protected $_email;
	protected $_password;
	protected $_role;
	
	// -------------------------------------------------------------------------------------------------------------------

	public function IsNull()
	{
		return !isset($this->_userId);
	}

	public function GetPartnerId()
	{
		$db = new DB();

		$query = "select user_id
			from {TABLEPREFIX}user
			where user_id != '".$this->get('userId')."'";
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
		where user_id != '".$this->get('userId')."'";
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