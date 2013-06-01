<?php
include_once '../_sf_appzone_security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

$accountsManager = new AccountsManager();
$accounts = $accountsManager->GetAllAccounts();

foreach ($accounts as $account)
{
	if ($account->getAccountId() != $_SESSION['account_id'])
		echo '<a href="#" onclick="javascript:ChangeAccount(\''.$account->getAccountId().'\'); return false;">';
	echo $account->getName();
	if ($account->getAccountId() != $_SESSION['account_id'])
		echo '</a>';
	echo ' / ';
}

?>