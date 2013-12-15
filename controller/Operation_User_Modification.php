<?php
class Operation_User_Modification extends Operation_User
{
	protected $_userId;
	protected $_name;
	protected $_email;
	protected $_delete;

	public function Save()
	{
		$handler = new UsersHandler();

		//if ($this->_delete == 'on')
		//	$handler->DeleteAccount($this->_accountId);
		//else
			$handler->UpdateUser($this->_userId, $this->_name, $this->_email);
	}
}