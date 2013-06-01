<?php
class Action_ChangeAccount extends Action
{
	protected $_accountId;

	public function setAccountId($accountId)
	{
		$this->_accountId = $accountId;
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
		$_SESSION['account_id'] = $this->_accountId;
	}
}