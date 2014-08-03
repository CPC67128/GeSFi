<h1><?= $translator->getTranslation('Saisir un virement') ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>
  <td style="vertical-align: middle;">

<?= $translator->getTranslation('Depuis le compte') ?> :<br/>
<?php
$accounts = $accountsManager->GetAllDuoAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br />
<?php } ?>

<input type="radio" name="fromAccount" value="USER/<?= $activeUser->getUserId() ?>"><i><?= $activeUser->getName() ?> / Compte inconnu</i><br />
<?php
$accounts = $accountsManager->GetAllPrivateAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $activeUser->get('name') ?> / <?= $account->get('name') ?><br />
<?php } ?>

<input type="radio" name="fromAccount" value="USER/<?= $activeUser->GetPartnerId() ?>"><i><?= $activeUser->GetPartnerName() ?> / Compte inconnu</i><br />

<br/>

<?= $translator->getTranslation('Vers le compte') ?> :<br/>
<?php
$accounts = $accountsManager->GetAllDuoAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="toAccount" value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br />
<?php } ?>
<?php
$accounts = $accountsManager->GetAllSharedLoans();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="toAccount" value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br />
<?php } ?>

<input type="radio" name="toAccount" value="USER/<?= $activeUser->get('userId') ?>"><i><?= $activeUser->get('name') ?> / Compte inconnu</i><br />
<?php
$accounts = $accountsManager->GetAllPrivateAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="toAccount" value="<?= $account->get('accountId') ?>"><?= $activeUser->get('name') ?> / <?= $account->get('name') ?><br />
<?php } ?>

<input type="radio" name="toAccount" value="USER/<?= $activeUser->GetPartnerId() ?>"><i><?= $activeUser->GetPartnerName() ?> / Compte inconnu</i><br />

<br/>

<?= $translator->getTranslation('Date') ?> <input title="aaaa-mm-jj hh:mm:ss" size="10" id="datePicker" name="date" value="<?php echo date("Y-m-d") ?>"><br/>
<?= $translator->getTranslation('Montant') ?> <input type="text" name="amount" size="6">&nbsp;&euro;<br/>
<?= $translator->getTranslation('Désignation') ?> <input type="text" name="designation" size="30" value="<?= $translator->getTranslation('Virement bancaire') ?>">

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