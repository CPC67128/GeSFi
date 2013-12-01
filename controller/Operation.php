<?php
class Operation
{
	protected $_db;
	protected $_currentAccountId;
	protected $_currentUserId;

	function __construct()
	{
		$this->_db = new DB();
		$this->_currentAccountId = $_SESSION['account_id'];
		$this->_currentUserId = $_SESSION['user_id'];
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
				$this->set('_'.$key, $value);
			}
		}
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
}