<h1><?= $translator->getTranslation('Situation des comptes') ?></h1>

<form id="form" action="/">
<table class="summaryTable">
<?php
$accountsManager = new AccountsManager();
$accounts = $accountsManager->GetAllAccounts();

foreach ($accounts as $account)
{
	if ($account->getType() != 2 && $account->getType() != 4)
	{
		$balance = $account->GetBalance();
?>
<tr>
<td><a href="#" onclick="javascript:ChangeAccount('<?= $account->getAccountId() ?>'); return false;"><?= $account->getName() ?></a></td>
<td style='text-align: right;<?php 
if ($balance <= $account->getExpectedMinimumBalance())
	echo 'background-color: #FF0000';
else
	echo 'background-color: #00FF00';
?>'><?= $translator->getCurrencyValuePresentation($balance) ?></td>
<td style='text-align: right; font-style:italic;'><?= $translator->getTranslation($account->getTypeDescription()) ?></td>
</tr>
<?php
	}
}
?>
</table>
