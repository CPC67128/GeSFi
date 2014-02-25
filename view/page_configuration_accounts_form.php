<?php
include '../security/security_manager.php';

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
<?= $translator->getTranslation('Description') ?> <input name='description' type='text' size='41' value="" /><br /> 
<?= $translator->getTranslation('Information') ?> <input name='information' type='text' size='41' value="" /><br /> 
<?= $translator->getTranslation('Type') ?> <select name="type">
<?php
$accountsManager = new AccountsManager();
$types = $accountsManager->GetAccountTypes();
foreach ($types as $key => $value)
{
	?><option value="<?= $key ?>"><?=  $value ?></option><?php
}
?>
</select><br />
<?= $translator->getTranslation('Titulaire') ?> <input name='owner' type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $_SESSION['user_id'] ?>" /> <i>(vous-même)</i><br />
<?= $translator->getTranslation('Co-titulaire') ?> <input name='coowner' type='text' size='41' /> <i>(l'identifiant de votre partenaire)</i><br />
<?= $translator->getTranslation('Solde initial') ?> <input name='openingBalance' type='text' size='7' value="0.00" /><?= $translator->getCurrencyPresentation() ?><br />
<?= $translator->getTranslation('Solde minimum') ?> <input name='expectedMinimumBalance' type='text' size='7' value="0.00" /><?= $translator->getCurrencyPresentation() ?><br />
<?= $translator->getTranslation('Date de creation') ?> <input title="aaaa-mm-jj" size="10" class="datePicker" name="creationDate" value="<?php echo date("Y-m-d") ?>"><br/>
<?= $translator->getTranslation('Date de clôture') ?> <input title="aaaa-mm-jj" size="10" class="datePicker" name="closingDate" value="<?php //echo date("Y-m-d",strtotime("+20 years")) ?>"><br/>
<?= $translator->getTranslation('Période de vérification minimale') ?> <input name="minimumCheckPeriod" type="text" size="4" value="30"><br/>
<?= $translator->getTranslation('Ordre') ?> <input name='sortOrder' type='text' size='5' /><br />
<?php
}
else
{
	$accountsManager = new AccountsManager();
	$account = $accountsManager->GetAccount($_POST['accountId']);
?>
<?= $translator->getTranslation('Identifiant') ?> <input type='text' name='accountId' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->get('accountId') ?>" /><br /> 
<?= $translator->getTranslation('Nom') ?> <input type='text' name='name' size='41' value="<?= $account->get('name') ?>" /><br />
<?= $translator->getTranslation('Description') ?> <input name='description' type='text' size='41' value="<?= $account->get('description') ?>" /><br /> 
<?= $translator->getTranslation('Information') ?> <input name='information' type='text' size='41' value="<?= $account->get('information') ?>" /><br /> 
<?= $translator->getTranslation('Type') ?> <input name="typeDescription" type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->getTypeDescription() ?>" /><br />
<?= $translator->getTranslation('Titulaire') ?> <input name='owner' type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->get('ownerUserId') ?>" /> / <input type='text' size='40' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->GetOwnerName() ?>" /><br />
<?= $translator->getTranslation('Co-titulaire') ?> <input name='coowner' type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->get('coownerUserId') ?>" /> / <input type='text' size='40' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->GetCoownerName() ?>" /><br />
<?= $translator->getTranslation('Date de création') ?> <input type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->get('creationDate') ?>" /><br />
<?= $translator->getTranslation('Solde initial') ?> <input name='openingBalance' type='text' size='7' value="<?= $account->get('openingBalance') ?>" /><?= $translator->getCurrencyPresentation() ?><br />
<?= $translator->getTranslation('Solde minimum') ?> <input name='expectedMinimumBalance' type='text' size='7' value="<?= $account->get('expectedMinimumBalance') ?>" /><?= $translator->getCurrencyPresentation() ?><br />
<?= $translator->getTranslation('Date de creation') ?> <input title="aaaa-mm-jj" size="10" class="datePicker" name="creationDate" value="<?php echo $account->get('creationDate') ?>"><br/>
<?= $translator->getTranslation('Date de clôture') ?> <input title="aaaa-mm-jj" size="10" class="datePicker" name="closingDate" value="<?php echo $account->get('closingDate') ?>"><br/>
<?= $translator->getTranslation('Période de vérification minimale') ?> <input name="minimumCheckPeriod" type="text" size="4" value="<?php echo $account->get('minimumCheckPeriod') ?>"><br/>
<?= $translator->getTranslation('Ordre') ?> <input name='sortOrder' type='text' size='5' value="<?= $account->get('sortOrder') ?>" /><br />
<br />
<font color='red'><?= $translator->getTranslation('Supprimer') ?> <input name='delete' type='checkbox' /></font> <i>Cocher pour clôturer le compte</i><br /><br />
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
		'../controller/controller.php?action=account_modification',
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

$( ".datePicker" ).datepicker({
	showOn: "both",
	buttonImage: "../media/calendar.gif",
	buttonImageOnly: true,
	dateFormat: "yy-mm-dd",
	firstDay: 1,
	dayNamesShort: [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ],
	dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
	dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
	monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ]
});
</script>