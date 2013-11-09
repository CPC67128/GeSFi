<?php
class Action_AddInvestmentValue extends Action
{
	protected $_date;
	protected $_value;
	protected $_designation;
	protected $_accountId;

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
			$this->set('_'.$key, $value);
		}
	}

	// -------------------------------------------------------------------------------------------------------------------

	public function Save()
	{
		$db = new DB();

		$db->InsertInvestmentValue(
				$_SESSION['account_id'],
				$this->_date,
				$this->_designation,
				$this->_value);
	}
}