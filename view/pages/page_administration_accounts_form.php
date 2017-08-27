<div id='formPlaceHolder'>
<form action="/" id="form">
<?php
$account = null;
$selectedTypeDescription = '';
if ($_POST['accountId'] != 'AddAccount')
{
	$account = $accountsHandler->GetAccount($_POST['accountId']);
	$selectedTypeDescription = $account->getTypeDescription();
}

function IfNullReturnEmptyString($account, $attribute)
{
	if (is_null($account))
		return '';
	return $account->get($attribute);
}

function IfNullReturnDefault($account, $attribute, $default)
{
	if (is_null($account))
		return $default;
	return $account->get($attribute);
}
?>

<?= $translator->getTranslation('Identifiant') ?> <input name='accountId' type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= IfNullReturnEmptyString($account, 'accountId') ?>" /><br />
<?= $translator->getTranslation('Nom') ?> <input type='text' name='name' size='41' value="<?= IfNullReturnEmptyString($account, 'name') ?>" /><br />

<?= $translator->getTranslation('Description') ?> <input name='description' type='text' size='41' value="<?= IfNullReturnEmptyString($account, 'description') ?>" /><br /> 

<?= $translator->getTranslation('Type') ?> <select name="type" <?= strlen($selectedTypeDescription) > 0 ? 'style="background-color : #d1d1d1;"  disabled="true"' : '' ?>>
<?php
$types = $accountsHandler->GetAccountTypes();
foreach ($types as $key => $value)
{
	?><option value="<?= $key ?>" <?= $value == $selectedTypeDescription ? 'selected' : '' ?>><?= $value ?></option><?php
}
?>
</select><br />

<?= $translator->getTranslation('Titulaire principal') ?> <input name='owner' type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= is_null($account) ? $_SESSION['user_id'] : $account->get('ownerUserId') ?>" />
<?php if (!is_null($account)) { ?>
 / <input type='text' size='40' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $account->GetOwnerName() ?>" />
<?php } else  { ?>
 <i>(vous-même)</i>
<?php } ?><br />

<?= $translator->getTranslation('Solde initial') ?> <input name='openingBalance' type='text' size='7' value="<?= IfNullReturnDefault($account, 'openingBalance', '0.00') ?>" /><?= $translator->getCurrencyPresentation() ?><br />
<?= $translator->getTranslation('Solde minimum') ?> <input name='expectedMinimumBalance' type='text' size='7' value="<?= IfNullReturnDefault($account, 'expectedMinimumBalance', '0.00') ?>" /><?= $translator->getCurrencyPresentation() ?><br />
<?= $translator->getTranslation('Date de creation') ?> <input title="aaaa-mm-jj" size="10" class="datePicker" name="creationDate" value="<?= IfNullReturnDefault($account, 'creationDate', date("Y-m-d")) ?>"><br/>
<?= $translator->getTranslation('Date de disponibilité') ?> <input title="aaaa-mm-jj" size="10" class="datePicker" name="availabilityDate" value="<?= IfNullReturnEmptyString($account, 'availabilityDate') ?>"><br/>
<?= $translator->getTranslation('Date de clôture') ?> <input title="aaaa-mm-jj" size="10" class="datePicker" name="closingDate" value="<?= IfNullReturnEmptyString($account, 'closingDate') ?>"><br/>
<?= $translator->getTranslation('Période de vérification minimale') ?> <input name="minimumCheckPeriod" type="text" size="4" value="<?= IfNullReturnDefault($account, 'minimumCheckPeriod', '30') ?>"><br/>
<?= $translator->getTranslation('Ordre') ?> <input name='sortOrder' type='text' size='5' value="<?= is_null($account) ? '' : $account->getIfSetOrDefault('sortOrder', 0) ?>" /><br />
<?= $translator->getTranslation('Confirmation des lignes') ?> <input name='recordConfirmation' type='checkbox' <?= is_null($account) ? '' : ($account->get('recordConfirmation') == "1" ? 'checked' : '') ?> /> <i><?= $translator->getTranslation('(Les lignes doivent être confirmées pour être prises en comptes)') ?></i><br />
<?= $translator->getTranslation('Ne pas afficher dans le menu') ?> <input name='notDisplayedInMenu' type='checkbox' <?= is_null($account) ? '' : ($account->get('notDisplayedInMenu') == "1" ? 'checked' : '') ?> /> <i><?= $translator->getTranslation('') ?></i><br />
<?= $translator->getTranslation('Ne pas colorier dans le récapitulatif gestion patrimoniale') ?> <input name='noColorInDashboard' type='checkbox' <?= is_null($account) ? '' : ($account->get('noColorInDashboard') == "1" ? 'checked' : '') ?> /> <i><?= $translator->getTranslation('') ?></i><br /><br />
<?= $translator->getTranslation('Peut générer des revenus') ?> <input name='generateIncome' type='checkbox' <?= is_null($account) ? '' : ($account->get('generateIncome') == "1" ? 'checked' : '') ?> /> <i><?= $translator->getTranslation('') ?></i><br /><br />

<?php if (!is_null($account)) { ?>
<font color='red'><?= $translator->getTranslation('Supprimer') ?> <input name='delete' type='checkbox' /></font> <i>Cocher pour clôturer le compte</i><br /><br />
<?php } ?>


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