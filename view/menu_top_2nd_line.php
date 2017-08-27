<?php
include 'menu.php';

$isGlobalRecordSelected = ($id == '' && $area == 'investment');

$accounts = $accountsHandler->GetAllInvestmentAccountsToDisplayInMenu();
$lastItem = end($accounts);
reset($accounts);

if ($lastItem == null)
	AddMenuTopItem(!$isGlobalRecordSelected, $translator->getTranslation('Gestion patrimoniale'), 'investment_record_dashboard', 'investment', '', '', false);
else
	AddMenuTopItem(!$isGlobalRecordSelected, $translator->getTranslation('Gestion patrimoniale'), 'investment_record_dashboard', 'investment', '', '', true);


foreach ($accounts as $account)
{
	$isAccountSelected = $account->get('accountId') == $id;
	$isLastItem = $lastItem->get('accountId') == $account->get('accountId');

	AddMenuTopItem(!$isAccountSelected, $account->get('name'), 'investment_record', 'investment', $account->get('accountId'), '', !$isLastItem);
}
