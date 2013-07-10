<?php
class Action_UserModification extends Action
{
	protected $_userId;
	protected $_name;
	protected $_email;
	protected $_delete;

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
	
	public function Execute()
	{
		$handler = new UsersHandler();

		//if ($this->_delete == 'on')
		//	$handler->DeleteAccount($this->_accountId);
		//else
			$handler->UpdateUser($this->_userId, $this->_name, $this->_email);
	}
}