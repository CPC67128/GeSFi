<?php
class Operation_User_Duo extends Operation_User
{
	protected $_userId;
	protected $_partnerUserId;
	protected $_delete;

	public function Save()
	{
		$handler = new UsersHandler();
		$handler->UpdateDuo($this->_userId, $this->_partnerUserId);
	}
}