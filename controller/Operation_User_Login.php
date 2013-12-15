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
		$user = $usersHandler->GetUserByEmail($_POST["email"]);

		if ($user == null)
			throw new Exception("Cette adresse email n'est pas enregistrée.");

		session_start();
		$_SESSION['email'] = $user->get('email');
		$_SESSION['user_id'] = $user->getUserId();
		$_SESSION['full_name'] = $user->getName();
		$_SESSION['read_only'] = $user->get('readOnly');
		
		//userHandler->RecordUserConnection($user->getUserId(), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);

		/*TODO
		$body = "Nouvelle connection de ".$_SESSION['email'];
		SendEmailToAdministrator("Nouvelle connection", $body);
		*/
	}
}