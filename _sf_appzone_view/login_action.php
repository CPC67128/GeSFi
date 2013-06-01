<?php
include_once('../dal/dal_appzone.php');
include_once('mailing.php');

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

if (security_IsPasswordCorrect($_POST["email"], $_POST["passwordMD5"]))
{
	$user_id = security_GetUserId($_POST["email"]);
	$row = security_GetUserRow($user_id);

	if (!$SECURITY_SINGLE_USER_MODE && $user_id == '0')
	{
		UnrecognizedUser();
		exit();
	}
	else
	{
		session_start();
		$_SESSION['email'] = $_POST["email"];
		$_SESSION['user_id'] = $user_id;
		$_SESSION['full_name'] = $row['full_name'];
		$_SESSION['read_only'] = $row['read_only'];
		security_RecordUserConnection($user_id, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
	
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