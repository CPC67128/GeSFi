<?php
include_once('../dal/dal_appzone.php');
include_once('../security/mailing.php');

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

$userHandler = new UsersHandler();

function UnrecognizedUser()
{
?>
<!-- ERROR -->
<div class="ui-widget">
<div class="ui-state-error ui-corner-all" style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em;">
<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
<strong>Identifiants non reconnus</strong>
</p>
</div>
</div>
<?php
}

function Success()
{
	die();
?>
<div class="ui-widget">
<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em;">
<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
Connecté !</p>
</div>
</div>
<?php
}

if (!(isset($_POST["email"]) && isset($_POST["passwordMD5"])))
{
	UnrecognizedUser();
	exit();
}

if (trim($_POST["email"]) == '' || trim($_POST["passwordMD5"]) == '')
{
	UnrecognizedUser();
	exit();
}

if ($userHandler->IsPasswordCorrect($_POST["email"], $_POST["passwordMD5"]))
{
	$user = $userHandler->GetUserByEmail($_POST["email"]);

	if (!$SECURITY_SINGLE_USER_MODE && $user->getUserId() == '0')
	{
		UnrecognizedUser();
		exit();
	}
	else
	{
		session_start();
		$_SESSION['email'] = $user->get('email');
		$_SESSION['user_id'] = $user->getUserId();
		$_SESSION['full_name'] = $user->getName();
		$_SESSION['read_only'] = $user->get('readOnly');

		$userHandler->RecordUserConnection($user->getUserId(), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
	
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