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
?>
<!doctype html>
<html>
<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="Description" content="Application en ligne gratuite de gestion financière du couple (compatibilité de couple ou comptabilité commune) écrite par Steve Fuchs">


<link rel="stylesheet" href="../3rd_party/jquery-ui-1.12.1/jquery-ui.min.css">
<script src="../3rd_party/jquery-3.2.1.min.js"></script>
<script src="../3rd_party/jquery-ui-1.12.1/jquery-ui.min.js"></script>

<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />

<title><?= $translator->getTranslation("GeSFi / Login") ?></title>
<link href="gesfi_login.css" rel="stylesheet" />
<script src="../3rd_party/md5.js"></script>

</head>
<body>
<table width="100%">
<tr>
<td valign="top" align="left">
<img src="../media/gfc.ico" /> GeSFi par <a href="http://stevefuchs.fr/">Steve Fuchs</a><br />
<br />
<a href="copyright.htm" target="blank">Licence et droit d’auteur</a>
</td>
<td valign="top" align="right">
<!-- Ad placeholder -->
</td>
</tr>
</table>
<br/>
<div class="centered">
<form id="saasLoginForm" action="/">
<table>

<tr>
<td colspan="2">
<table>
<tr>
	<td>Utilisateur</td>
	<td>
	<?php
	$users = $usersHandler->GetAllUsers();
	$checked = "checked";
	foreach ($users as $user) { ?>
	<input type="radio" name="name" value="<?= $user->get('name') ?>" <?= $checked ?>> <?= $user->get('name') ?><br>
	<?php $checked = ""; } ?>
	</td>
</tr>
<tr>
	<td>Mot de passe</td>
	<td><input type="password" name="password" id="password" size="35" /></td>
</tr>
<tr>
    <td><i>Code de sécurité</i></td>
    <td><i><input id="passwordMD5" style="background-color : #d1d1d1;" readonly="readonly" type="text" name="passwordMD5" size="35" autocomplete="off" value="" /></i></td>
</tr>
</table>
</td>
</tr>

<tr>
<td align="right"><input value="Se connecter" name="submit" type="submit"></td>
</tr>

<tr>
<td colspan="2"><div id="saasLoginFormResult"></div></td>
</tr>

</table>
</form>

</div>
</body>

<script>


function HashPassword() {
	pw = $("#password").val();
	md5 = MD5(pw);
	$("#passwordMD5").val(md5);
}

$(function () {
	$('#password').keyup(function() {
		HashPassword();
	});

	$("#saasLoginForm").submit(function() {
		HashPassword();
		$("#password").val('');
	
		$.post(
				'../controller/controller.php?action=user_login',
				$(this).serialize(),
				function(response, status){
					$("#saasLoginFormResult").stop().show();
					if (status == 'success') {
						$("#saasLoginFormResult").html(response);
						if (response.indexOf("<!-- ERROR -->") < 0) {
							window.location="index.php";
						}
					}
					else {
						$("#saasLoginFormResult").html(CreateUnexpectedErrorWeb("Status = " + status));
					}
	
					setTimeout(function() {
						$("#saasLoginFormResult").fadeOut("slow", function () {
							$('#saasLoginFormResult').empty();
						})
					}, 4000);
				}
		);
		return false;
	});
});

HashPassword();

function CreateUnexpectedErrorWeb($error)
{
	var html = '<div class="ui-widget">';
	html += '<div class="ui-state-error ui-corner-all" style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em;">';
	html += '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
	html += '<strong>Unexpected error</strong>' + $error + '</p>';
	html += '</div></div>';
	return html;
}
</script>

</html>