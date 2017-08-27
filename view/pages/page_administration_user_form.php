<div id='formPlaceHolder'>
<form action="/" id="formUser">
<?php
$isNew = ($_POST['userId'] == 'AddUser');

if ($isNew)
	$user = new User();
else
	$user = $usersHandler->GetUser($_POST['userId']);
?>

<?= $translator->getTranslation("Identifiant unique") ?> <input type='text' name='userId' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $isNew ? '' : $user->get('userId') ?>" /><br />
<br />
<?= $translator->getTranslation("Nom") ?> <input type='text' name='name' size='41' value="<?= $isNew ? '' : $user->get('name') ?>" /><br /> 
<i><?= $translator->getTranslation("Ce nom est affiché dans les différents écrans de l'application") ?></i><br />
<br /> 
<?= $translator->getTranslation("Email") ?> <input type='text' name='email' size='41' value="<?= $isNew ? '' : $user->get('email') ?>" /><br />
<i><?= $translator->getTranslation("L'email est utilisé pour les communications de l'application") ?></i><br />
<br />
<?php if (!$isNew) { ?><input name='setNewPassword' type='checkbox'/><?php } ?>
<?= $translator->getTranslation("Nouveau mot de passe") ?> <input type='text' name='password' id='password' size='41' value="" />
<input id="passwordMD5" style='background-color : #d1d1d1;' readonly="readonly" type="text" name="passwordMD5" id="passwordMD5" size="20" autocomplete="off" value="" /><br />
<i><?= $translator->getTranslation("Saisir le nouveau mot de passe en cochant la case") ?></i><br />
<br />
<?= $translator->getTranslation('Rôle') ?> <select name="role">
<?php
$roles = $usersHandler->GetRolesList();
foreach ($roles as $key => $value)
{
	?><option <?= $isNew ? '' : ($user->get('role') == $key ? 'selected' : '') ?> value="<?= $key ?>"><?= $translator->getTranslation($value) ?></option><?php
}
?>
</select><br />
<br />

<input type="submit" id='submitFormUser' value="<?= $isNew ? $translator->getTranslation("Ajouter") : $translator->getTranslation("Mettre à jour") ?>" />
<div id='formUserResult'></div>
</form>
</div>

<script type="text/javascript" src="../3rd_party/md5.js"></script>
<script type='text/javascript'>
function CalculatePasswordMD5()
{
	pw = $("#password").val();
	md5 = MD5(pw);
	$("#passwordMD5").val(md5);
}
CalculatePasswordMD5();

$("#formUser").submit( function () {
	document.getElementById("submitFormUser").disabled = true;

	CalculatePasswordMD5();

	$.post (
		'../controller/controller.php?action=user_modification',
		$(this).serialize(),
		function(response, status) {
			$("#formUserResult").stop().show();
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					$("#formUserResult").html(response);
				}
				else {
					$("#formUserResult").html(response);
					listUsers();
				}
			}
			else {
				$("#formUserResult").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitFormUser").disabled = false;

			setTimeout(function() {
				$("#formUserResult").fadeOut("slow", function () {
					$('#formUserResult').empty();
				})
			}, 4000);
		}
	);
	return false;
});

$('#password').keyup(function(){
	CalculatePasswordMD5();
});
</script>