<?php
include_once('../dal/dal_appzone.php');
include_once('mailing.php');
include '../configuration/3rd_party.php';

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

if (!(isset($_POST["email"]) && isset($_POST["passwordMD5"])))
{
	BadFields('');
	exit;
}

if (trim($_POST["email"]) == '' || trim($_POST["passwordMD5"]) == '')
{
	BadFields('');
	exit;
}

if (security_IsEmailExisting($_POST["email"]))
{
	BadFields('Email déjà enregistré');
	exit;
}

if (!security_IsEmailAddressGood($_POST["email"]))
{
	BadFields('Mauvaise adresse email');
	exit;
}

// reCaptcha verification
require_once($THIRD_PARTY_FOLDER.'recaptcha-php/recaptchalib.php');
$privatekey = "6Ld6LNYSAAAAADITZeCMzL3vquoz7AqDOoeBsO3O";
$resp = recaptcha_check_answer ($privatekey,
		$_SERVER["REMOTE_ADDR"],
		$_POST["recaptcha_challenge_field"],
		$_POST["recaptcha_response_field"]);

if (!$resp->is_valid)
{
	BadFields('Captcha non vérifié');
	exit;
}

if (security_CreateUser($_POST["email"], $_POST["fullName"], $_POST["passwordMD5"]))
{
	$body = "Inscription de ".$_POST['email'];
	SendEmailToAdministrator("Inscription", $body);

	Success();
}
else
{
	BadFields();
}
?>