<?php
include '../component/component_autoload.php';
include '../component/component_mail.php';
include '../configuration/configuration.php';

$usersHandler = new UsersHandler();
$translator = new Translator();

$usersHandler->StartSession();

// ========== Single User Security Mode

// In this mode, the user is automaticaly loged in using the user id defined in the configuration file
if ($SECURITY_SINGLE_USER_MODE)
{
	$usersHandler->SetSessionUser($SECURITY_SINGLE_USER_MODE_USER_ID);
	$usersHandler->RecordUserConnection();

	// Redirect to index page
	if (isset($_SESSION['go_to']) && $_SESSION['go_to'] != '')
	{
		header('Location: '.$_SESSION['go_to'], true, 301);
		$_SESSION['go_to'] = '';
	}
	else
		header('Location: index.php', true, 301);

	exit();
}

// ========== Multiple User Security Mode

// If user is already connected
if ($usersHandler->IsSessionUserSet())
{
	header('Location: index.php', true, 301);

    exit();
}

// Login is given as GET parameter / In this mode, the user id is given as a GET parameter
if (!empty($_GET['autologin']))
{
	$user = $usersHandler->GetUser($_GET['autologin']);

	if (!$user->IsNull())
	{
		if (!empty($_GET['autologinpwd']))
			$pwd = $_GET['autologinpwd'];
		else
			$pwd = 'd41d8cd98f00b204e9800998ecf8427e';

		if ($user->IsPasswordCorrect($pwd))
		{
			$usersHandler->SetSessionUser($user->get('userId'));
			$usersHandler->RecordUserConnection();

			SendEmailToAdministrator("Nouvelle connection", "Nouvelle connection en autlogin de ".$user->get('name'));
		}

		header("Location: index.php", true, 301);

		exit();
	}
}