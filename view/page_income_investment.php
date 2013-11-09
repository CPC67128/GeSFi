<h1><?= $translator->getTranslation('Saisir un versement sur un placement') ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>
  <td style="vertical-align: middle;">

<?= $translator->getTranslation('Vers le placement :') ?><br/>

<br/>

<?php
$accountsManager = new AccountsManager();
$accounts = $accountsManager->GetAllInvestmentAccounts();

foreach ($accounts as $account)
{
?>
<input type="radio" name="accountId" <?= $account->getAccountId() == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->getAccountId() ?>"><?= $account->getDescription() ?><br />
<?php
}
?>

<br/>


<br/>
<?= $translator->getTranslation('Date') ?> <input title="aaaa-mm-jj hh:mm:ss" size="10" id="datePicker" name="date" value="<?php echo date("Y-m-d") ?>"><br/>
<?= $translator->getTranslation('Versement') ?> <input type="text" name="payment" tabindex="-1" size="6">&nbsp;&euro;<br/>
<?= $translator->getTranslation('Montant investi') ?> <input type="text" name="paymentInvested" tabindex="-1" size="6">&nbsp;&euro;<br/>
<?= $translator->getTranslation('Valorisation') ?> <input type="text" name="value" tabindex="-1" size="6">&nbsp;&euro;<br/>
<?= $translator->getTranslation('Désignation') ?> <input type="text" name="designation" id="designation" size="30" value="<?= $translator->getTranslation('Versement') ?>">
</td>

<td style="vertical-align: middle;">
<?= $translator->getTranslation('Périodicité:') ?><br/>
<input type="radio" name="periodicity" value="unique" checked><?= $translator->getTranslation('unique') ?></input><br />
<input type="radio" name="periodicity" value="monthly"><?= $translator->getTranslation('tous les mois') ?></input><br />
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