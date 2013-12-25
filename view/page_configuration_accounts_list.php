<?php
include '../security/security_manager.php';

function __autoload($class_name)
{
	$file = '../controller/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../model/'.$class_name . '.php';
	include $file;
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
<option value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?> (<?= $account->get('sortOrder') ?>)</option>
<?php
}
?>
</select> 