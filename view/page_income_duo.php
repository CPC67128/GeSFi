<h1><?= $translator->getTranslation('Saisir un versement') ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>
  <td style="vertical-align: middle;">
	  <?= $translator->getTranslation('Effectuée par') ?><input type="radio" name="actor" value="1" checked><?= $activeAccount->GetOwnerName() ?> </input><?= $translator->getTranslation('ou'); ?> <input type="radio" name="actor" value="2"><?= $activeAccount->GetCoownerName() ?></input>
	  <br/><br/>	
	  <?= $translator->getTranslation('Depuis le compte') ?> :
  	  <br/>
  	  <input type="radio" name="fromAccount" checked value=""><i>non défini</i><br />
  	  <?php
  	  $accounts = $accountsManager->GetAllPrivateAccountsForDuo($activeAccount->getAccountId());
  	  foreach ($accounts as $account)
  	  {
  	  ?>
  	  <input type="radio" name="fromAccount" value="<?= $account->getAccountId() ?>"><?= $account->GetOwnerName() ?> : <?= $account->getName() ?><br />
  	  <?php
  	  }
  	  ?>
	  <br/>
  	  <?= $translator->getTranslation('Vers') ?> <input type="radio" name="toAccount" value="duo" onClick="javascript: onDestinationChange('duo');" checked><?= $translator->getTranslation('le compte commun') ?> </input><?= $translator->getTranslation('ou'); ?> <input type="radio" name="toAccount" onClick="javascript: onDestinationChange('private');" value="private"><?= $translator->getTranslation('son partenaire'); ?></input>
	  <br/>
	  <?= $translator->getTranslation('Date') ?> <input title="aaaa-mm-jj hh:mm:ss" size="10" id="datePicker" name="date" value="<?php echo date("Y-m-d") ?>">
	  <br/>
  	  <?= $translator->getTranslation('Montant') ?> <input type="text" name="amount" size="6">&nbsp;&euro;
	  <br/>
  	  <?= $translator->getTranslation('Désignation') ?> <input type="text" name="designation" id="designation" size="30" value="<?= $translator->getTranslation('Versement sur compte commun') ?>">
  </td>
  <td style="vertical-align: middle;">
	  <?= $translator->getTranslation('Périodicité:') ?>
	  <br/>
	  <input type="radio" name="periodicity" value="unique" checked><?= $translator->getTranslation('unique') ?></input>
	  <br />
	  <input type="radio" name="periodicity" value="monthly"><?= $translator->getTranslation('tous les mois') ?></input>
	  <br />
	  <?= $translator->getTranslation('pendant') ?> <input type="text" name="periodicityNumber" size="3"> <?= $translator->getTranslation('mois') ?>
  </td>
</tr>
</table>
<br />
<input value="<?= $translator->getTranslation('Ajouter') ?>" id="submitForm" type="submit">
<input id="resetForm" name="reset" value="<?= $translator->getTranslation('Effacer') ?>" type="reset">
<input type='button' value='<?= $translator->getTranslation('Annuler') ?>' onclick='LoadRecords();' />
<div id="formResult"></div>
</form>
<script type="text/javascript">
function onDestinationChange(destination)
{
	if (destination == 'duo')
		document.getElementById("designation").value = '<?= $translator->getTranslation('Versement sur compte commun') ?>';
	else
		document.getElementById("designation").value = '<?= $translator->getTranslation('Versement au partenaire') ?>';
}
</script>