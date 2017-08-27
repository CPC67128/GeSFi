<?php
class Operation_User_Modification extends Operation_User
{
	protected $_setNewPassword;
	protected $_deactivate;

	public function Validate()
	{
		$this->ValidateName_Structure();
		$this->ValidateName_Inexistence();

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
		if (!empty($this->_userId))
		{
			// Existing user update
			$this->_usersHandler->UpdateUser($this->_userId, $this->_name, $this->_email, $this->_role);

			if ($this->_setNewPassword > 0)
				$this->_usersHandler->UpdateUserPassword($this->_userId, $this->_passwordMD5);

			$this->_usersHandler->UpdateUserActive($this->_userId, $this->_active);
		}
	}
}