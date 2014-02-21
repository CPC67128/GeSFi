<h1><?= $translator->getTranslation('Ecrire une remarque') ?></h1>

<form action="/" id="form">
<table class="actionsTable">
<tr>
  <td style="vertical-align: middle;">
<?php
$accountsManager = new AccountsManager();
$fromAccount = $activeAccount;

if ($fromAccount->get('type') == 2)
{
?>
	  <?= $translator->getTranslation('Effectuée par') ?><input type="radio" name="actor" value="1" checked><?= $activeAccount->GetOwnerName() ?> </input><?= $translator->getTranslation('ou'); ?> <input type="radio" name="actor" value="2"><?= $activeAccount->GetCoownerName() ?></input>
	  <br/>
<?php
}
else
{
?>
<input type="hidden" name="actor" value="1" />
<?php
}
?>
	  <?= $translator->getTranslation('Date') ?> <input title="aaaa-mm-jj hh:mm:ss" size="10" id="datePicker" name="date" value="<?php echo date("Y-m-d") ?>">
	  <br/>
	  <?= $translator->getTranslation('Désignation') ?> <input type="text" name="designation" size="30">
  </td>
</tr>
</table>
<br />
<input value="<?= $translator->getTranslation('Ajouter') ?>" id="submitForm" type="submit">
<input id="resetForm" name="reset" value="<?= $translator->getTranslation('Effacer') ?>" type="reset">
<input type='button' value='<?= $translator->getTranslation('Annuler') ?>' onclick='LoadRecords();' />
<div id="formResult"></div>
</form>