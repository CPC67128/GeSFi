<?php
class Operation_User_Subscription extends Operation_User
{
	public function Validate()
	{
		$this->ValidateEmail();
		$this->ValidateEmail_Structure();
		$this->ValidatePasswordMD5();
		$this->ValidatePassword();
		$this->ValidatePasswordConfirmation();
		$this->ValidateCaptcha();
	}

	public function Save()
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUserByEmail($_POST["email"]);
		if ($user != null)
			throw new Exception("Cette adresse email est déjà enregistrée.");

		$this->_db->InsertUser(
				$this->_email,
				$this->_name,
				$this->_passwordMD5);
		/*TODO$body = "Inscription de ".$_POST['email'];
		SendEmailToAdministrator("Inscription", $body);*/
	}

	public function IsSessionRequired()
	{
		return false;
	}
}