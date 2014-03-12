<h1><?= $translator->getTranslation('Saisir un versement sur un placement') ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>
  <td style="vertical-align: middle;">

<?= $translator->getTranslation('Depuis le compte') ?> :<br/>
<input type="radio" name="fromAccount" value=""><i>Compte inconnu</i><br />
<?php
$accounts = $accountsManager->GetAllPrivateAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br />
<?php } ?>
<?php
$accounts = $accountsManager->GetAllDuoAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br />
<?php } ?>
<br/>
<br/>

<?= $translator->getTranslation('Date du prélèvement') ?> <input title="aaaa-mm-jj hh:mm:ss" size="10" class="datePicker" name="fromDate" value="<?php echo date("Y-m-d") ?>"><br/>
<br/>

<?= $translator->getTranslation('Vers le placement :') ?><br/>
<?php
$accountsManager = new AccountsManager();
$accounts = $accountsManager->GetAllInvestmentAccounts();

foreach ($accounts as $account)
{
?>
<input type="radio" name="toAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><?= strlen($account->get('description')) > 0 ? ' ('.$account->get('description').')' : ''  ?><br />
<?php
}
?>

<br/>
<?= $translator->getTranslation('Date de prise en compte') ?> <input title="aaaa-mm-jj hh:mm:ss" size="10" class="datePicker" name="toDate" value="<?php echo date("Y-m-d") ?>"><br/>
<br/>
<?= $translator->getTranslation('Versement') ?> <input type="text" name="payment" size="6">&nbsp;&euro;<br/>
<?= $translator->getTranslation('Montant investi') ?> <input type="text" name="paymentInvested" size="6">&nbsp;&euro;<br/>
<?= $translator->getTranslation('Valorisation') ?> <input type="text" name="value" size="6">&nbsp;&euro; (optionnel)<br/>
<?= $translator->getTranslation('Désignation') ?> <input type="text" name="designation" id="designation" size="30" value="<?= $translator->getTranslation('Versement vers placement') ?>">
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