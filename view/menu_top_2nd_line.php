<?php
include 'menu.php';

$isGlobalRecordSelected = ($id == '' && $area == 'investment');

AddMenuTopItem(!$isGlobalRecordSelected, $translator->getTranslation('Gestion patrimoniale'), 'investment_record_dashboard', 'investment', '', '', true);

$accounts = $accountsHandler->GetAllInvestmentAccountsToDisplayInMenu();
$lastItem = end($accounts);
reset($accounts);

foreach ($accounts as $account)
{
	$isAccountSelected = $account->get('accountId') == $id;
	$isLastItem = $lastItem->get('accountId') == $account->get('accountId');

	AddMenuTopItem(!$isAccountSelected, $account->get('name'), 'record', 'investment', $account->get('accountId'), '', !$isLastItem);
}
