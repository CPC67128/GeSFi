<h1><?= $translator->getTranslation('Saisir un retrait depuis un placement') ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>
  <td style="vertical-align: top;">

<?= $translator->getTranslation('Depuis le placement') ?> :<br/>
<?php
$accountsHandler = new AccountsHandler();
$accounts = $accountsHandler->GetAllInvestmentAccounts();

foreach ($accounts as $account)
{
?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><?= strlen($account->get('description')) > 0 ? ' ('.$account->get('description').')' : ''  ?><br />
<?php
}
?>
<br/>

<?= $translator->getTranslation('Date') ?> <input type="hidden" id="datePickerHidden" name="fromDate" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline"></div><br/>
<br/>

<?= $translator->getTranslation('Vers le compte :') ?><br/>
<input type="radio" name="toAccount" value=""><i>Compte inconnu</i><br />
<?php
$accounts = $accountsHandler->GetAllPrivateAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="toAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br />
<?php } ?>

<br/>
<?= $translator->getTranslation('Date') ?> <input type="hidden" id="datePickerHidden2" name="toDate" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline2"></div><br/>
<br/>
<?= $translator->getTranslation('Montant désinvesti') ?> <input type="text" name="amountDisinvested" size="6">&nbsp;&euro;<br/>
<?= $translator->getTranslation('Désignation') ?> <input type="text" name="designation" id="designation" size="30" value="<?= $translator->getTranslation('Retrait depuis placement') ?>">
</td>

<!-- 
<td style="vertical-align: middle;">
<?= $translator->getTranslation('Périodicité:') ?><br/>
<input type="radio" name="periodicity" value="unique" checked><?= $translator->getTranslation('unique') ?></input><br />
<input type="radio" name="periodicity" value="monthly"><?= $translator->getTranslation('tous les mois') ?></input><br />
<?= $translator->getTranslation('pendant') ?> <input type="text" name="periodicityNumber" size="3"> <?= $translator->getTranslation('mois') ?>
</td>
-->

</tr>
</table>
<br />
<input value="<?= $translator->getTranslation('Ajouter') ?>" id="submitForm" type="submit">
<input id="resetForm" name="reset" value="<?= $translator->getTranslation('Effacer') ?>" type="reset">
<input type='button' value='<?= $translator->getTranslation('Annuler') ?>' onclick='LoadRecords();' />
<div id="formResult"></div>
</form>