<?php
include_once '../security/security_manager.php';
include '../configuration/security.php';
include_once '../dal/dal_appzone.php';
include_once 'mailing.php';

function BadFields($Message)
{
	if ($Message == '')
		$Message = 'Merci de remplir correctement les différents champs';

?>
<!-- ERROR -->
<div class="ui-widget">
<div class="ui-state-error ui-corner-all" style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em;">
<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
<strong><?php echo $Message; ?></strong>
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
Enregistré !</p>
</div>
</div>
<?php
}

if (!$SECURITY_SINGLE_USER_MODE && (!security_IsEmailAddressGood($_POST["email"])))
{
	BadFields('Adresse email invalide');
	exit;
}

if (security_UpdateUser($USER_ID, $_POST["email"], $_POST["fullName"], $SECURITY_SINGLE_USER_MODE ? '' : $_POST["passwordMD5"]))
{
	$body = "Inscription de ".$_POST['email'];
	SendEmailToAdministrator("Inscription", $body);

	$_SESSION['email'] = $_POST["email"];
	$_SESSION['full_name'] = $_POST['fullName'];

	Success();
}
else
{
	BadFields();
}
?>