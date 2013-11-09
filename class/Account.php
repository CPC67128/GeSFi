<?php
class Account
{
	protected $_accountId;
	protected $_name;
	protected $_description;
	protected $_type;
	protected $_ownerUserId;
	protected $_coownerUserId;
	protected $_openingBalance;
	protected $_expectedMinimumBalance;
	protected $_information;
	protected $_creationDate;

	protected $_sortOrder;

	public function setAccountId($accountId)
	{
		$this->_accountId = $accountId;
	}
	
	public function getAccountId()
	{
		return $this->_accountId;
	}
	
	public function setName($accountName)
	{
		$this->_name = $accountName;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function setType($type)
	{
		$this->_type = $type;
	}

	public function getType()
	{
		return $this->_type;
	}

	public function getTypeDescription()
	{
		switch ($this->_type)
		{
			case 1: return 'Compte privé'; break;
			case 2: return 'Compte duo virtuel'; break;
			case 3: return 'Compte duo'; break;
			case 4: return 'Compte d\'optimisation financière'; break;
			case 10: return 'Placement privé'; break;
		}
	}

	public function setOpeningBalance($openingBalance)
	{
		$this->_openingBalance = $openingBalance;
	}
	
	public function getOpeningBalance()
	{
		return $this->_openingBalance;
	}
	
	public function setExpectedMinimumBalance($expectedMinimumBalance)
	{
		$this->_expectedMinimumBalance = $expectedMinimumBalance;
	}
	
	public function getExpectedMinimumBalance()
	{
		return $this->_expectedMinimumBalance;
	}
	
	public function setOwnerUserId($ownerUserId)
	{
		$this->_ownerUserId = $ownerUserId;
	}
	
	public function getOwnerUserId()
	{
		return $this->_ownerUserId;
	}
	
	public function setCoownerUserId($coownerUserId)
	{
		$this->_coownerUserId = $coownerUserId;
	}
	
	public function getCoownerUserId()
	{
		return $this->_coownerUserId;
	}
	
	public function setCreationDate($creationDate)
	{
		$this->_creationDate = $creationDate;
	}

	public function getCreationDate()
	{
		return $this->_creationDate;
	}
	
	public function setSortOrder($sortOrder)
	{
		$this->_sortOrder = $sortOrder;
	}
	
	public function getSortOrder()
	{
		return $this->_sortOrder;
	}

	public function setDescription($description)
	{
		$this->_description = $description;
	}
	
	public function getDescription()
	{
		return $this->_description;
	}

	public function setInformation($information)
	{
		$this->_information = $information;
	}
	
	public function getInformation()
	{
		return $this->_information;
	}

	public function hydrate(array $data)
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

	public function GetInvestmentLastValue()
	{
		$db = new DB();
	
		$query = 'select value
			from {TABLEPREFIX}investment_record
			where account_id = \''.$this->_accountId.'\'
			and value is not null
			order by record_date desc
			limit 1';
		$row = $db->SelectRow($query);
	
		return $row['value'];
	}

	
}