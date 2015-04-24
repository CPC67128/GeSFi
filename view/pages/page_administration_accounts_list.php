<?php
$translator = new Translator();

$accountsHandler = new AccountsHandler();
$accounts = $accountsHandler->GetAllAccounts();
?>
<select id="accountsList" name="accountsList" size="<?= count($accounts) + 1 ?>" onChange="changeAccount(this)">
<option value="AddAccount" selected>Ajouter un nouveau compte...</option>
<?php
foreach ($accounts as $account)
{
?>
<option value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?> (<?= $account->getIfSetOrDefault('sortOrder', 0) ?>)</option>
<?php
}
?>
</select>