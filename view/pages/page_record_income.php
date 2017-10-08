<h1><?= t("Saisir un versement / revenu") ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>

<td>
<?php include 'page_record_inc_categories_table.php'; ?>
</td>

<td>
<?= t("Vers le compte") ?><br><br>
<?php
$accounts = $accountsHandler->GetAllDuoAccounts();
foreach ($accounts as $account) { ?>
<input type="radio" name="toAccount" <?= $account->get("accountId") == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get("accountId") ?>"><?= $account->get("name") ?><br>
<?php } ?>
<br>
<input type="radio" name="toAccount" value="USER/<?= $activeUser->get("userId") ?>"><i><?= $activeUser->get("name") ?> / Compte inconnu</i><br>
<?php
$accounts = $accountsHandler->GetAllPrivateAccounts();
foreach ($accounts as $account) { ?>
<input type="radio" name="toAccount" <?= $account->get("accountId") == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get("accountId") ?>"><?= $activeUser->get("name") ?> / <?= $account->get("name") ?><br>
<?php } ?>
</td>

<td>
<?= t("Date") ?> <input type="hidden" id="datePickerHidden" name="date" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline"></div><br>
<?= t("Montant") ?> <input type="text" name="amount" style='background-color : #d1d1d1;' tabindex="-1" size="6" readonly>&nbsp;&euro;<br>
<?= t("Désignation") ?> <input class="ui-autocomplete-input" type="text" name="designation" id="designation" size="30"><br><br>
<?= t("Confirmer l'opération") ?> <input type="checkbox" name="confirmed" id="confirmed" />
<?php AddFormButton(); ?>
</td>

<td>
<?php AddReccurenceSubForm(); ?>
</td>

</tr>
</table>
</form>

<?php include 'page_record_inc_designation_autocomplete.php'; ?>