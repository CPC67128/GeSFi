<?php
class Operation_User_Login extends Operation_User
{
	public function Validate()
	{
		$this->ValidateEmail();
		$this->ValidatePasswordMD5();
	}

	public function Save()
	{
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUserByUserName($_POST["email"]);

		if ($user == null)
			throw new Exception("Cet utilisateur n'est pas enregistré.");

		if(!isset($_SESSION))
		{
			session_start();
		}

		$_SESSION['user_name'] = $user->get('userName');
		$_SESSION['email'] = $user->get('email');
		$_SESSION['user_id'] = $user->get('userId');
		$_SESSION['full_name'] = $user->get('name');
		$_SESSION['read_only'] = 0;

		$this->_usersHandler->RecordUserConnection($user->get('userId'), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);

		/*TODO
		$body = "Nouvelle connection de ".$_SESSION['email'];
		SendEmailToAdministrator("Nouvelle connection", $body);
		*/
	}

	public function IsSessionRequired()
	{
		return false;
	}
}