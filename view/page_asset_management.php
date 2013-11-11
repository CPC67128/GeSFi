<table class="blankTable">
<thead>
<th>
<b><?= $translator->getTranslation('Patrimoine personnel') ?></b>
</th>
<th>
<b><?= $translator->getTranslation('Patrimoine du couple') ?></b>
</th>
</thead>
<tr>

<td>



<table class="summaryTable">
<tr>
<td colspan="5"><b><?= $translator->getTranslation('Gestion courante') ?></b></td>
</tr>
<?php
$accountsManager = new AccountsManager();
$sum = 0;
$sumGlobal = 0;

$accounts = $accountsManager->GetAllPrivateAccounts();

foreach ($accounts as $account)
{
	global $sum;

	$balance = $account->GetBalance();
	?>
<tr>
<td><?= $account->getName() ?></td>
<td style='text-align: right;'><?= $translator->getCurrencyValuePresentation($balance) ?></td>
</tr>
<?php
	$sum += $balance;
}
?>
<tr>
<td><i><?= $translator->getTranslation('Sous-total') ?></i></td>
<td style='text-align: right;'><i><?= $translator->getCurrencyValuePresentation($sum) ?></i></td>
</tr>



<tr>
<td colspan="5"><b><?= $translator->getTranslation('Placements') ?></b></td>
</tr>
<?php
$accounts = $accountsManager->GetAllInvestmentAccounts();

$sumGlobal += $sum;
$sum = 0;

foreach ($accounts as $account)
{
	global $sum;
?>
<tr>
<td><?= $account->getName() ?></td>
<td style='text-align: right;'><?= $translator->getCurrencyValuePresentation($account->GetInvestmentLastValue()) ?></td>
<td style='text-align: right;' <?= $account->GetInvestmentLastYield() < 0 ? 'bgcolor="red"' : '' ?>><?= $translator->getPercentagePresentation($account->GetInvestmentLastYield()) ?></td>
<td style='text-align: right;' <?= $account->GetInvestmentLastYieldAverage() < 0 ? 'bgcolor="red"' : '' ?>><?= $translator->getPercentagePresentation($account->GetInvestmentLastYieldAverage()) ?></td>
<td style='text-align: right;' <?= ($account->GetInvestmentLastValueDate() != '' && strtotime($account->GetInvestmentLastValueDate()) < strtotime("-1 month")) ? 'bgcolor="red"' : '' ?>><?= $account->GetInvestmentLastValueDate() != '' ? $account->GetInvestmentLastValueDate() : '' ?></td>
</tr>
<?php
	$sum += $account->GetInvestmentLastValue();
}

$sumGlobal += $sum;
?>
<tr>
<td><i><?= $translator->getTranslation('Sous-total') ?></i></td>
<td style='text-align: right;'><i><?= $translator->getCurrencyValuePresentation($sum) ?></i></td>
</tr>
<tr>
<td><b>Total</b></td>
<td style='text-align: right;'><b><?= $translator->getCurrencyValuePresentation($sumGlobal) ?></b></td>
</tr>
</table>


</td>
<td>

<table class="summaryTable">
<tr>
<td colspan="2"><b><?= $translator->getTranslation('Gestion courante') ?></b></td>
</tr>
<?php
$accountsManager = new AccountsManager();
$sum = 0;
$sumGlobal = 0;

$accounts = $accountsManager->GetAllDuoAccounts();

foreach ($accounts as $account)
{
	global $sum;

	$balance = $account->GetBalance();
	?>
<tr>
<td><?= $account->getName() ?></td>
<td style='text-align: right;'><?= $translator->getCurrencyValuePresentation($balance) ?></td>
</tr>
<?php
	$sum += $balance;
}

$sumGlobal = $sum;
?>
<tr>
<td><i><?= $translator->getTranslation('Sous-total') ?></i></td>
<td style='text-align: right;'><i><?= $translator->getCurrencyValuePresentation($sum) ?></i></td>
</tr>
<tr>
<td colspan="2"><b><?= $translator->getTranslation('Placements') ?></b></td>
</tr>
<tr>
<td><i><?= $translator->getTranslation('Sous-total') ?></i></td>
<td style='text-align: right;'><i><?= $translator->getCurrencyValuePresentation(0) ?></i></td>
</tr>
<tr>
<td><b>Total</b></td>
<td style='text-align: right;'><b><?= $translator->getCurrencyValuePresentation($sumGlobal) ?></b></td>
</tr>
</table>

</td>
</tr>
</table>
