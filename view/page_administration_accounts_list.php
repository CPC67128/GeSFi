<?php
include '../security/security_manager.php';

$translator = new Translator();

$accountsHandler = new AccountsHandler();
$accounts = $accountsHandler->GetAllAccounts();
?>
<select name="accountsList" size="<?= count($accounts) + 1 ?>" onChange="changeAccount(this)">
<option value="AddAccount">Ajouter un nouveau compte...</option>
<?php
foreach ($accounts as $account)
{
?>
<option value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?> (<?= $account->get('sortOrder') ?>)</option>
<?php
}
?>
</select> 