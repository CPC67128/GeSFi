<?php
include '../component/component_autoload.php';
$translator = new Translator();
?>
<!doctype html>
<html>
<head>
<?php include '../component/component_head.php'; ?>
<title><?= $translator->getTranslation("GeSFi / Inscription") ?></title>
<link href="page_subscription.css" rel="stylesheet" />
<script src="../3rd_party/md5.js"></script>
<script src="page_subscription.js"></script>

<meta http-equiv="expires" content="0">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache, must-revalidate">
</head>

<body>
<h1>Formulaire d'inscription</h1>
<form id="subscriptionForm" action="/">
<table>
<tr>
<td>Email</td>
<td><input type="text" name="email" size="20" autocomplete="off" value="" /> <font color="red"><strong>*</strong></font></td>
</tr>
<tr>
<td>Nom complet</td>
<td><input type="text" name="name" size="20" autocomplete="off" value="" /></td>
</tr>
<tr>
<td>Mot de passe</td>
<td><input id="password" type="password" name="password" size="20" autocomplete="off" value="" /> <font color="red"><strong>*</strong></font></td>
</tr>
<tr>
<td>Confirmation</td>
<td><input id="passwordConfirmation" type="password" name="passwordConfirmation" size="20" autocomplete="off" value="" /> <font color="red"><strong>*</strong></font></td>
</tr>
<tr>
<td><i>Hash</i></td>
<td><input id="passwordMD5" style='background-color : #d1d1d1;' readonly="readonly" type="text" name="passwordMD5" size="20" autocomplete="off" value="" /></td>
</tr>
</table>
<br />
Vérification captcha : <font color="red"><strong>*</strong></font><br />
<?php
require_once('../3rd_party/recaptcha-php/recaptchalib.php');
$publickey = "6Ld6LNYSAAAAAPOsMcXZymlFhIevhg8UEnY2eE_D";
echo recaptcha_get_html($publickey);
?>
<br />
<input value="S'enregistrer" name="submit" type="submit">
&nbsp;&nbsp;
<input value="Annuler" id="cancelButton" type="button">

<div id="subscriptionFormResult"></div>
</form>

</body>
</html>