<?php
class Investment
{
	protected $_investmentId;
	protected $_userId;
	protected $_name;

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
				case 'investment_id': $key = 'investmentId'; break;
				default: $key = $key; break;
			}
			$this->set('_'.$key, $value);
	
			$this->_isNull = false;
		}
	}


	public function AAAAAAAAAhydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			switch ($key)
			{
				case 'account_id': $key = 'AccountId'; break;
				case 'opening_balance': $key = 'OpeningBalance'; break;
				case 'expected_minimum_balance': $key = 'ExpectedMinimumBalance'; break;
				case 'owner_user_id': $key = 'OwnerUserId'; break;
				case 'coowner_user_id': $key = 'CoownerUserId'; break;
				case 'creation_date': $key = 'CreationDate'; break;
				case 'sort_order': $key = 'SortOrder'; break;
				default: $key = ucfirst($key); break;
			}
			$method = 'set'.$key;
			if (method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}

	// -------------------------------------------------------------------------------------------------------------------

	public function GetLastValue()
	{
		$db = new DB();
	/*
		$query = 'select sum(amount) as total
			from {TABLEPREFIX}record
			where record_type between 10 and 19
			and marked_as_deleted = 0
			and account_id = \''.$this->_accountId.'\'
			and record_date <= curdate()';
		$row = $db->SelectRow($query);

		return $row['total'];
		*/
		return 100;
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
		$usersHandler = new UsersHandler();
		$currentUser = $usersHandler->GetCurrentUser();

		$db = new DB();
	
		$query = 'select category, category_id
		from {TABLEPREFIX}category
		where link_type = \'DUO\'
		and link_id = \''.$currentUser->getDuoId().'\'
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
}