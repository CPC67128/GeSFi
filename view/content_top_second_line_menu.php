<?php
include_once '../security/security_manager.php';

function __autoload($class_name)
{
	$file = '../controller/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../model/'.$class_name . '.php';
	include $file;
}

$accountsManager = new AccountsManager();

if ($_SESSION['page'] != 'asset_management')
	echo '<a href="#" onclick="javascript:ChangeContext(\'asset_management\',\'\',\'\'); return false;">';
echo 'Gestion patrimoniale';
if ($_SESSION['page'] != 'asset_management')
	echo '</a>';
echo ' / ';

$accounts = $accountsManager->GetAllInvestmentAccounts();

foreach ($accounts as $account)
{
	if ($account->get('accountId') != $_SESSION['account_id'])
		echo '<a href="#" onclick="javascript:ChangeContext(\'records\',\''.$account->get('accountId').'\',\'\'); return false;">';
	echo $account->get('name');
	if ($account->get('accountId') != $_SESSION['account_id'])
		echo '</a>';
	echo ' / ';
}

