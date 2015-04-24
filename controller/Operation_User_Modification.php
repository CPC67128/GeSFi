<?php
class Operation_User_Modification extends Operation_User
{
	protected $_setNewPassword;
	protected $_deactivate;
	protected $_partnerUserId;
	protected $_updateDuo;

	public function Validate()
	{
		$this->ValidateUserName_Structure();
		$this->ValidateUserName_Inexistence();

		if (!empty($this->_updateDuo))
			$this->_updateDuo = 1;
		else
			$this->_updateDuo = 0;

		if (!empty($this->_deactivate))
			$this->_active = 0;
		else
			$this->_active = 1;

		if (!empty($this->_setNewPassword))
			$this->_setNewPassword = 1;
		else
			$this->_setNewPassword = 0;

		if (!empty($this->_email))
			$this->ValidateEmail_Structure();

		if (!empty($this->_setNewPassword))
		{
			$this->ValidatePasswordMD5();
		}
	}

	public function Save()
	{
		if (empty($this->_userId))
		{
			// New user creation
			$this->_usersHandler->InsertUser($this->_userName,$this->_name, $this->_email, $this->_passwordMD5, $this->_role);
		}
		else
		{
			// Existing user update
			$this->_usersHandler->UpdateUser($this->_userId, $this->_userName,$this->_name, $this->_email, $this->_role);

			if ($this->_setNewPassword > 0)
				$this->_usersHandler->UpdateUserPassword($this->_userId, $this->_passwordMD5);

			$this->_usersHandler->UpdateUserActive($this->_userId, $this->_active);
		}
		//if ($this->_delete == 'on')
		//	$handler->DeleteAccount($this->_accountId);
		//else
			//$handler->UpdateUser($this->_userId, $this->_name, $this->_email);
	}
}