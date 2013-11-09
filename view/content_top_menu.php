<?php
include_once '../security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

if ($_SESSION['account_id'] != 'all_accounts')
	echo '<a href="#" onclick="javascript:ChangeAccount(\'all_accounts\'); return false;">';
echo 'Gestion courante';
if ($_SESSION['account_id'] != 'all_accounts')
	echo '</a>';
echo ' / ';

$accountsManager = new AccountsManager();
$accounts = $accountsManager->GetAllOrdinaryAccounts();

foreach ($accounts as $account)
{
	if ($account->getAccountId() != $_SESSION['account_id'])
		echo '<a href="#" onclick="javascript:ChangeAccount(\''.$account->getAccountId().'\'); return false;">';
	echo $account->getName();
	if ($account->getAccountId() != $_SESSION['account_id'])
		echo '</a>';
	echo ' / ';
}

if ($_SESSION['account_id'] != 'configuration')
	echo '<a href="#" onclick="javascript:ChangeAccount(\'configuration\'); return false;">';
echo 'Configuration';
if ($_SESSION['account_id'] != 'configuration')
	echo '</a>';
?>