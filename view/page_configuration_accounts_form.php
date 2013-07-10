<?php
include '../_sf_appzone_security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

$translator = new Translator();
?>
<div id='formPlaceHolder'>
<form action="/" id="form">
<?php
if ($_POST['accountId'] == 'AddAccount')
{
?>
<?= $translator->getTranslation('Identifiant') ?> <input name='accountId' type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="" /><br />
<?= $translator->getTranslation('Nom') ?> <input name='name' type='text' size='41' value="" /><br /> 
<?= $translator->getTranslation('Type') ?> <select name="type">
<option value="1">Compte privé</option>
<option value="2">Compte duo virtuel</option>
<option value="3">Compte duo</option>
<option value="4">Compte d'optimisation financière</option>
</select><br />
<?= $translator->getTranslation('Titulaire') ?> <input name='owner' type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $_SESSION['user_id'] ?>" /> <i>(vous-même)</i><br />
<?= $translator->getTranslation('Co-titulaire') ?> <input name='coowner' type='text' size='41' /> <i>(l'identifiant de votre partenaire)</i><br />
<?= $translator->getTranslation('Solde initial') ?> <input name='openingBalance' type='text' size='7' value="0.00" /><?= $translator->getCurrencyPresentation() ?><br />
<?= $translator->getTranslation('Solde minimum') ?> <input name='expectedMinimumBalance' type='text' size='7' value="0.00" /><?= $translator->getCurrencyPresentation() ?><br />
<?= $translator->getTranslation('Ordre') ?> <input name='sortOrder' type='text' size='5' /><br />
<?php
}
else
{
	$accountsManager = new AccountsManager();
	$account = $accountsManager->GetAccount($_POST['accountId']);
?>
<?= $translator->getTranslation('Identifiant') ?> <input type='text' name='accountId' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->getAccountId() ?>" /><br /> 
<?= $translator->getTranslation('Nom') ?> <input type='text' name='name' size='41' value="<?= $account->getName() ?>" /><br /> 
<?= $translator->getTranslation('Type') ?> <input name="typeDescription" type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->getTypeDescription() ?>" /><br />
<?= $translator->getTranslation('Titulaire') ?> <input name='owner' type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->getOwnerUserId() ?>" /> / <input type='text' size='40' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->GetOwnerName() ?>" /><br />
<?= $translator->getTranslation('Co-titulaire') ?> <input name='coowner' type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->getCoownerUserId() ?>" /> / <input type='text' size='40' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->GetCoownerName() ?>" /><br />
<?= $translator->getTranslation('Date de création') ?> <input type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->getCreationDate() ?>" /><br />
<?= $translator->getTranslation('Solde initial') ?> <input name='openingBalance' type='text' size='7' value="<?= $account->getOpeningBalance() ?>" /><?= $translator->getCurrencyPresentation() ?><br />
<?= $translator->getTranslation('Solde minimum') ?> <input name='expectedMinimumBalance' type='text' size='7' value="<?= $account->getExpectedMinimumBalance() ?>" /><?= $translator->getCurrencyPresentation() ?><br />
<?= $translator->getTranslation('Ordre') ?> <input name='sortOrder' type='text' size='5' value="<?= $account->getSortOrder() ?>" /><br />
<br />
<font color='red'><?= $translator->getTranslation('Supprimer') ?> <input name='delete' type='checkbox' /></font> <i>Cocher pour supprimer le compte</i><br /><br />
<?php
}
?>
<input type="submit" id='submitForm' value="Envoyer" />
</div>
<div id='formResult'></div>
</form>

<script type='text/javascript'>
$("#form").submit( function () {
	document.getElementById("submitForm").disabled = true;
	$.post (
		'controller.php?action=accountModification',
		$(this).serialize(),
		function(response, status) {
			$("#formResult").stop().show();
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					$("#formResult").html(response);
				}
				else {
					$("#formResult").html(response);
					$("#formPlaceHolder").html('');
					listAccounts();
					LoadTopMenu();
				}
			}
			else {
				$("#formResult").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitForm").disabled = false;

			setTimeout(function() {
				$("#formResult").fadeOut("slow", function () {
					$('#formResult').empty();
				})
			}, 4000);
		}
	);
	return false;
});
</script>