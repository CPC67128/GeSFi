<h1><?= t("Saisir un virement") ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>

<td>
<?= t("Depuis le compte") ?><br>
<?php
$accounts = $accountsHandler->GetAllDuoAccounts();
foreach ($accounts as $account) { ?>
<input type="radio" name="fromAccount" <?= $account->get("accountId") == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get("accountId") ?>"><?= $account->get("name") ?><br>
<?php } ?>
<input type="radio" name="fromAccount" value="USER/<?= $activeUser->get("userId") ?>"><i><?= $activeUser->get("name") ?> / Compte inconnu</i><br>
<?php
$accounts = $accountsHandler->GetAllSharedLoans();
foreach ($accounts as $account) { ?>
<input type="radio" name="fromAccount" <?= $account->get("accountId") == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get("accountId") ?>"><?= $activeUser->get("name") ?> / <?= $account->get("name") ?><br>
<?php }
$accounts = $accountsHandler->GetAllPrivateAccounts();
foreach ($accounts as $account) { ?>
<input type="radio" name="fromAccount" <?= $account->get("accountId") == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get("accountId") ?>"><?= $activeUser->get("name") ?> / <?= $account->get("name") ?><br>
<?php } ?>
<input type="radio" name="fromAccount" value="USER/<?= $activeUser->GetPartnerId() ?>"><i><?= $activeUser->GetPartnerName() ?> / Compte inconnu</i><br>
</td>

<td>
<?= t("Vers le compte") ?><br>
<?php
$accounts = $accountsHandler->GetAllDuoAccounts();
foreach ($accounts as $account) { ?>
<input type="radio" name="toAccount" value="<?= $account->get("accountId") ?>"><?= $account->get("name") ?><br>
<?php }
$accounts = $accountsHandler->GetAllSharedLoans();
foreach ($accounts as $account) { ?>
<input type="radio" name="toAccount" value="<?= $account->get("accountId") ?>"><?= $account->get("name") ?><br>
<?php } ?>
<input type="radio" name="toAccount" value="USER/<?= $activeUser->get("userId") ?>"><i><?= $activeUser->get("name") ?> / Compte inconnu</i><br>
<?php
$accounts = $accountsHandler->GetAllPrivateAccounts();
foreach ($accounts as $account) { ?>
<input type="radio" name="toAccount" value="<?= $account->get("accountId") ?>"><?= $activeUser->get("name") ?> / <?= $account->get("name") ?><br>
<?php } ?>
<input type="radio" name="toAccount" value="USER/<?= $activeUser->GetPartnerId() ?>"><i><?= $activeUser->GetPartnerName() ?> / Compte inconnu</i><br>
</td>

<td>
<?= t("Date") ?> <input type="hidden" id="datePickerHidden" name="date" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline"></div><br>
<?= t("Montant") ?> <input type="text" name="amount" size="6">&nbsp;&euro;<br>
<?= t("Désignation") ?> <input type="text" name="designation" size="30" value="<?= t("Virement bancaire") ?>"><br><br>
<?= t("Confirmer l'opération") ?> <input type="checkbox" name="confirmed" id="confirmed" />
<?php AddFormButton(); ?>
</td>

<td>
<?php AddReccurenceSubForm(); ?>
</td>

</tr>
</table>
</form>