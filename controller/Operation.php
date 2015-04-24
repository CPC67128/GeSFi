<?php
class Operation
{
	protected $_db;
	protected $_currentAccountId;
	protected $_currentUserId;
	protected $_accountsHandler;
	protected $_usersHandler;

	function __construct()
	{
		$this->_db = new DB();
		if (isset($_SESSION['account_id']))
			$this->_currentAccountId = $_SESSION['account_id'];
		if (isset($_SESSION['user_id']))
			$this->_currentUserId = $_SESSION['user_id'];

		$this->_accountsHandler = new AccountsHandler();
		$this->_usersHandler = new UsersHandler();
	}

	// -------------------------------------------------------------------------------------------------------------------

	public function hydrate(array $data)
	{
		foreach ($data as $key => $value)
		{
			$method = 'set'.ucfirst($key);
			if (method_exists($this, $method))
			{
				$this->$method($value);
			}
			else
			{
				$this->set($key, $value);
			}
		}
	}

	public function set($member, $value)
	{
		$member = '_'.$member;
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

	public function Execute()
	{
		$this->Validate();
		$this->Save();
	}

	public function Validate()
	{
	}

	public function Save()
	{
	}

	public function ParseAmount($amount)
	{
		$amount = str_replace(' ' ,'', $amount);
		$amount = str_replace(',' ,'.', $amount);
		$amount = str_replace('€' ,'', $amount);

		return $amount;
	}

	public function IsSessionRequired()
	{
		return true;
	}
}