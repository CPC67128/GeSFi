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

	protected $_isNull = true;

	public function setEmail($email)
	{
		$this->_email = $email;
	}
	
	public function getName()
	{
		return $this->_name;
	}
	
	public function setPassword($password)
	{
		$this->_password = $password;
	}
	
	public function getPassword()
	{
		return $this->_password;
	}
	
	public function setSubscriptionDate($subscriptionDate)
	{
		$this->_subscriptionDate = $subscriptionDate;
	}
	
	public function getSubscriptionDate()
	{
		return $this->_subscriptionDate;
	}
	public function getDuoId()
	{
		return $this->_duoId;
	}
		
	public function set($member, $value)
	{
		$this->$member = $value;
	}

	public function get($member)
	{
		$member = '_'.$member;
		if (isset($this->$member))
			return $this->$member;
		else
			throw new Exception('Unknow attribute '.$member);
	}

	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			switch ($key)
			{
				case 'user_id': $key = 'userId'; break;
				case 'user_name': $key = 'userName'; break;
				case 'subscription_date': $key = 'subscriptionDate'; break;
				case 'read_only': $key = 'readOnly'; break;
				case 'duo_id': $key = 'duoId'; break;
				default: $key = $key; break;
			}
			$this->set('_'.$key, $value);

			$this->_isNull = false;
		}
	}

	// -------------------------------------------------------------------------------------------------------------------

	public function IsNull()
	{
		return $this->_isNull;
	}

	public function GetTotalIncome()
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type between 10 and 19
			and marked_as_deleted = 0
			and account_id = \''.$this->_accountId.'\'
			and record_date <= curdate()';
		$row = $db->SelectRow($query);

		return $row['total'];
	}

	public function GetTotalOutcome()
	{
		$db = new DB();

		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type between 20 and 29
			and marked_as_deleted = 0
			and account_id = \''.$this->_accountId.'\'
			and record_date <= curdate()';
		$row = $db->SelectRow($query);

		return $row['total'];
	}

	public function GetBalance()
	{
		$balance = $this->getOpeningBalance() + $this->GetTotalIncome() - $this->GetTotalOutcome();
		return $balance;
	}
	
	public function GetPlannedOutcome($numberOfDays)
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 1
			and marked_as_deleted = 0
			and creation_date > curdate()
			and account_id = \''.$this->_accountId.'\'
			and record_date < adddate(curdate(), interval +'.$numberOfDays.' day)';
		$row = $db->SelectRow($query);

		return $row['total'];
	}
	
	public function GetOwnerName()
	{
		$db = new DB();
	
		$query = 'select name from {TABLEPREFIX}user
		where user_id = \''.$this->_ownerUserId.'\'';
		$row = $db->SelectRow($query);
	
		return $row['name'];
	}

	public function GetOwnerEmail()
	{
		$db = new DB();
	
		$query = 'select email from {TABLEPREFIX}user
		where user_id = \''.$this->_ownerUserId.'\'';
		$row = $db->SelectRow($query);
	
		return $row['email'];
	}

	public function GetCoownerName()
	{
		$db = new DB();
	
		$query = 'select name from {TABLEPREFIX}user
		where user_id = \''.$this->_coownerUserId.'\'';
		$row = $db->SelectRow($query);
	
		return $row['name'];
	}

	public function GetCoownerEmail()
	{
		$db = new DB();
	
		$query = 'select email from {TABLEPREFIX}user
		where user_id = \''.$this->_coownerUserId.'\'';
		$row = $db->SelectRow($query);
	
		return $row['email'];
	}

	public function GetDuoCategoriesForOutcome()
	{
		$db = new DB();
	
		$query = 'select category, category_id
		from {TABLEPREFIX}category
		where link_type = \'DUO\'
		and link_id in (\''.$this->_ownerUserId.'\', \''.$this->_coownerUserId.'\')
		and type = 1
		order by sort_order';
		$result = $db->Select($query);
		return $result;
	}

	public function GetUserCategoriesForOutcome()
	{
		$db = new DB();
	
		$query = 'select category, category_id
			from {TABLEPREFIX}category
			where link_type = \'USER\'
			and link_id = \'{USERID}\'
			and type = 1
			order by sort_order';
		$result = $db->Select($query);
	
		return $result;
	}

	public function GetUserCategoriesForIncome()
	{
		$db = new DB();
	
		$query = 'select category, category_id
			from {TABLEPREFIX}category
			where link_type = \'USER\'
			and link_id = \'{USERID}\'
			and type = 0
			order by sort_order';
		$result = $db->Select($query);

		return $result;
	}

	public function GetTotalCreditByActorAndMonthAndYear($actor, $month, $year)
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type >= 10 and record_type < 20 
			and marked_as_deleted = 0
			and record_date <= curdate()
			and record_date_month = ".$month."
			and record_date_year = ".$year."
			and actor = ".$actor."
			and account_id = '".$this->_accountId."'";
		$row = $db->SelectRow($query);

		return $row['total'];
	}

	public function GetTotalCredit()
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type = 3
			and marked_as_deleted = 0
			and record_date <= curdate()
			and account_id = '".$this->_accountId."'";
		$row = $db->SelectRow($query);

		return $row['total'];
	}

	public function GetTotalCreditByActor()
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type in (3, 0)
			and marked_as_deleted = 0
			and record_date <= curdate()
			and account_id = '".$this->_accountId."'";
		$row = $db->SelectRow($query);

		return $row['total'];
	}

	public function GetTotalDebitByMonthAndYear($month, $year)
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type >= 20 and record_type < 30
			and marked_as_deleted = 0
			and record_date <= curdate()
			and record_date_month = ".$month."
			and record_date_year = ".$year."
			and account_id = '".$this->_accountId."'";
		$row = $db->SelectRow($query);

		return $row['total'];
	}

	public function GetTotalDebit()
	{
		$db = new DB();
	
		$query = "select sum(amount) as total
		from {TABLEPREFIX}record
		where record_type in (4, 20)
		and marked_as_deleted = 0
		and record_date <= curdate()
		and account_id = '".$this->_accountId."'";
		$row = $db->SelectRow($query);
	
		return $row['total'];
	}

	function GetTotalIncomeJointAccountByActor($actor)
	{
		$db = new DB();
	
		$query = 'select sum(amount) as total
		from {TABLEPREFIX}record
		where record_type in (3, 10)
		and marked_as_deleted = 0
		and account_id in (select account_id from {TABLEPREFIX}account where type in (2, 3) and (owner_user_id = \'{USERID}\' or coowner_user_id = \'{USERID}\'))
		and record_date <= curdate()
		and actor = '.$actor;
		$row = $db->SelectRow($query);
	
		return $row['total'];
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

	function HasPartner()
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
			return true;
		}
	
		return false;
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

	/*
	function RecordConnection($Ip, $Browser)
	{
		$db = new DB();

		$query = sprintf("insert into {TABLEPREFIX}user_connection (user_id, connection_date_time, ip_address, browser) values('%s', now(), '%s', '%s')",
				$this->_userId,
				$Ip,
				$db->ConvertStringForSqlInjection($Browser));

		$result = $db->Execute($query);
	
		return true;
	}
	*/
}