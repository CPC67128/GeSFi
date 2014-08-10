
	else
	{
		session_start();
		$_SESSION['email'] = $user->get('email');
		$_SESSION['user_id'] = $user->get('userId');
		$_SESSION['full_name'] = $user->getName();
		$_SESSION['read_only'] = $user->get('readOnly');

		$userHandler->RecordUserConnection($user->get('userId'), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
	
		$body = "Nouvelle connection de ".$_SESSION['email'];
		SendEmailToAdministrator("Nouvelle connection", $body);
	
		Success();
	}
}
else
{
	UnrecognizedUser();
}
?>