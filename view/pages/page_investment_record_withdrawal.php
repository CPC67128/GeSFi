<h1><?= t('Saisir un retrait depuis un placement') ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>
  <td style="vertical-align: top;">

<?= t('Depuis le placement') ?> :<br>
<?php
$accounts = $accountsHandler->GetAllInvestmentAccounts();
foreach ($accounts as $account) { ?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><?= strlen($account->get('description')) > 0 ? ' ('.$account->get('description').')' : ''  ?><br>
<?php } ?>
<br>
<?= t('Date') ?> <input type="hidden" id="datePickerHidden" name="fromDate" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline"></div><br>
</td>

<td>
<?= t('Vers le compte :') ?><br>
<input type="radio" name="toAccount" value=""><i>Compte inconnu</i><br>
<?php
$accounts = $accountsHandler->GetAllPrivateAccounts();
foreach ($accounts as $account) { ?>
<input type="radio" name="toAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br>
<?php } ?>
<br>
<?= t('Date') ?> <input type="hidden" id="datePickerHidden2" name="toDate" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline2"></div><br>
</td>

<td>
<?= t('Montant désinvesti') ?> <input type="text" name="amountDisinvested" size="6">&nbsp;&euro;<br>
<?= t('Désignation') ?> <input type="text" name="designation" id="designation" size="30" value="<?= t('Retrait depuis placement') ?>"><br><br>
<?= t("Confirmer l'opération") ?> <input type="checkbox" name="confirmed" id="confirmed" />
<?php AddFormButton(); ?>
</td>

</tr>
</table>
</form>