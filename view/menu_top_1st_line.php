<?php
include 'menu.php';

$isGlobalRecordSelected = ($id == '' && $area == '');

AddMenuTopItem(!$isGlobalRecordSelected, $translator->getTranslation('Gestion courante'), 'record', '', '', '', true); 

$accounts = $accountsHandler->GetAllOrdinaryAccounts();

foreach ($accounts as $account)
{
	$isAccountSelected = $account->get('accountId') == $id;

	AddMenuTopItem(!$isAccountSelected, $account->get('name'), 'record', '', $account->get('accountId'), '', true);
}

$isConfigurationSelected = ($page == 'administration');

AddMenuTopItem(!$isConfigurationSelected, $translator->getTranslation('Administration'), 'administration', 'administration', '', '', false);
