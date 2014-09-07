<?php
include_once '../security/security_manager.php';

$accountsHandler = new AccountsHandler();

if ($_SESSION['page'] != 'investment_records_dashboard' && $_SESSION['page'] != 'investment_records_statistics')
	echo '<a href="#" onclick="javascript:ChangeContext(\'investment_records_dashboard\',\'\',\'\'); return false;">';
echo 'Gestion patrimoniale';
if ($_SESSION['page'] != 'investment_records_dashboard' && $_SESSION['page'] != 'investment_records_statistics')
	echo '</a>';
echo ' / ';

$accounts = $accountsHandler->GetAllInvestmentAccounts();

foreach ($accounts as $account)
{
	if ($account->get('accountId') != $_SESSION['account_id'])
		echo '<a href="#" onclick="javascript:ChangeContext(\'records\',\''.$account->get('accountId').'\',\'asset_management\'); return false;">';
	echo $account->get('name');
	if ($account->get('accountId') != $_SESSION['account_id'])
		echo '</a>';
	echo ' / ';
}
