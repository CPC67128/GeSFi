<?php
class Action_AccountModification extends Action
{
	protected $_accountId;
	protected $_name;
	protected $_type;
	protected $_owner;
	protected $_coowner;
	protected $_openingBalance;
	protected $_expectedMinimumBalance;
	protected $_delete;
	
	public function setAccountId($accountId)
	{
		$this->_accountId = $accountId;
	}

	public function setName($name)
	{
		if (strlen(trim($name)) <= 0)
			throw new Exception('Le nom est invalide');

		$this->_name = $name;
	}

	public function setType($type)
	{
		$this->_type = $type;
	}

	public function setOwner($owner)
	{
		$this->_owner = $owner;
	}

	public function setCoowner($coowner)
	{
		$this->_coowner = $coowner;
	}

	public function setOpeningBalance($openingBalance)
	{
		$this->_openingBalance = $openingBalance;
	}

	public function setExpectedMinimumBalance($expectedMinimumBalance)
	{
		$this->_expectedMinimumBalance = $expectedMinimumBalance;
	}

	public function setDelete($delete)
	{
		$this->_delete = $delete;
	}
	
	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			$method = 'set'.ucfirst($key);
			if (method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}

	// -------------------------------------------------------------------------------------------------------------------
	
	public function Execute()
	{
		$handler = new AccountsManager();

		if ($this->_accountId == '')
		{
			$handler->InsertAccount($this->_name, $this->_owner, $this->_coowner, $this->_type, $this->_openingBalance, $this->_expectedMinimumBalance);
		}
		else
		{
			if ($this->_delete == 'on')
				$handler->DeleteAccount($this->_accountId);
			else
				$handler->UpdateAccount($this->_accountId, $this->_name, $this->_openingBalance, $this->_expectedMinimumBalance);
		}
	}
}