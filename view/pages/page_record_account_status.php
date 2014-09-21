<?php
$totalPlannedDebit = 0;
$accountsHandler = new AccountsHandler();
if ($accountType == 0)
{
	?>
	<h1><?= $translator->getTranslation('Situation des comptes') ?></h1>

	<table class="summaryTable">
	<?php
	$accounts = $accountsHandler->GetAllOrdinaryAccounts();
	
	foreach ($accounts as $account)
	{
		
		if ($account->get('type') != 2 && $account->get('type') != 4)
		{
			$balance = $account->GetBalance();
			?>
	
			<tr>
			<td><a href="#" onclick="javascript:ChangeContext('record','','<?= $account->get('accountId') ?>',''); return false;"><?= $account->get('name') ?></a></td>
			<td style='text-align: right;<?php 
			if ($account->get('type') != 5)
			{
				if ($balance <= $account->get('expectedMinimumBalance'))
					echo 'background-color: #FF0000';
				else
					echo 'background-color: #00FF00';
			}
			?>'><?= $translator->getCurrencyValuePresentation($balance) ?></td>
			<td style='text-align: right; font-style:italic;'><?= $translator->getTranslation($account->getTypeDescription()) ?></td>
			</tr>

			<?php
			$totalPlannedDebit += $account->GetPlannedOutcome(10);
		}
	}
	?>
	</table>
	<br />
<?php
}
?>

<?php
$accountPlannedDebit = $activeAccount->GetPlannedOutcome(10);

// ------------ Affichage du solde d'un compte réel
if ($accountType == 4)
{
	$balance = $activeAccount->GetBalance();
?>
Epargne : <?= $translator->getCurrencyValuePresentation($balance) ?>
<?php
}
else if ($accountType != 2 && $accountType != 0)
{
	$accountExpectedMinimumBalance = $activeAccount->get('expectedMinimumBalance');
	$balance = $activeAccount->GetBalance();
	if ($activeAccount->get('recordConfirmation') == 1)
		$balanceConfirmed = $activeAccount->GetBalanceConfirmed();

	$criticalAccountBalance = false;
	if ($accountExpectedMinimumBalance >= ($balance + $accountPlannedDebit))
		$criticalAccountBalance = true;

	if ($criticalAccountBalance)
		echo "<font color='red'>";
	else
		echo "<font color='green'>";
?>
Solde : <?= $translator->getCurrencyValuePresentation($balance) ?></font>
<?php
if ($activeAccount->get('recordConfirmation') == 1)
{
?>
 / <?= $translator->getCurrencyValuePresentation($balanceConfirmed) ?> confirmé 
<?php
}
?>
(Débit prévus pour les 10 prochains jours : <?= $translator->getCurrencyValuePresentation($accountPlannedDebit) ?>)
<?php
}
else
{
?>
Débit prévus pour les 10 prochains jours : <?= $translator->getCurrencyValuePresentation($totalPlannedDebit) ?>
<?php
}
?>
<br /><br />