<?php
include '../security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

$translator = new Translator();

$accountsManager = new AccountsManager();
$accounts = $accountsManager->GetAllAccounts();
?>
<select name="accountsList" size="<?= count($accounts) + 1 ?>" onChange="changeAccount(this)">
<option value="AddAccount">Ajouter un nouveau compte...</option>
<?php
foreach ($accounts as $account)
{
?>
<option value="<?= $account->getAccountId() ?>"><?= $account->getName() ?> (<?= $account->getSortOrder() ?>)</option>
<?php
}
?>
</select> 