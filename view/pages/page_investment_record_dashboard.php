<table class="blankTable">
	<thead>
		<th><b><?= $translator->getTranslation('Patrimoine personnel') ?></b>
		</th>
		<th><b><?= $translator->getTranslation('Patrimoine partagé') ?></b></th>
	</thead>
	<tr>

		<td>

			<table class="summaryTable">
				<tr>
					<td colspan="7"><b><?= $translator->getTranslation('Gestion courante') ?></b></td>
				</tr>
<?php
/***** Private Assets *****/

$accountsHandler = new AccountsHandler();
$sum = 0;
$sumGlobal = 0;

$accounts = $accountsHandler->GetAllPrivateAccounts();

foreach ($accounts as $account)
{
	global $sum;

	$balance = $account->GetBalance();
	?>
<tr>
					<td><a href="#"
						onclick="javascript:ChangeContext('record','','<?= $account->get('accountId')?>',''); return false;"><?= $account->get('name') ?></a></td>
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
					<td colspan="7"><b><?= $translator->getTranslation('Placements') ?></b></td>
				</tr>
<?php
$accounts = $accountsHandler->GetAllPrivateInvestmentAccounts();

$sumGlobal += $sum;
$sum = 0;


function PrintTDStyleAttributeForAccountName($yieldAverage, $yield)
{
	if (strlen($yieldAverage) != 0)
	{
		if ($yieldAverage < -1)
			echo 'bgcolor="Red"';
		elseif ($yieldAverage < 0)
			echo 'bgcolor="Tomato"';
		elseif ($yieldAverage < 1)
			echo 'bgcolor="orange"';
		elseif ($yieldAverage < 3)
			echo 'bgcolor="lightgreen"';
		elseif ($yieldAverage >= 3)
			echo 'bgcolor="GreenYellow"';
	}
	else if (strlen($yield) != 0)
	{
		if ($yield < -1)
			echo 'bgcolor="Red"';
		elseif ($yield < 0)
			echo 'bgcolor="Tomato"';
	}
}

function PrintTDStyleAttributeForYield($value)
{
	if (strlen($value) != 0)
	{
		if ($value < -1)
			echo 'bgcolor="Red"';
		elseif ($value < 0)
			echo 'bgcolor="Tomato"';
	}
}

function PrintTDStyleAttributeForYieldAverage($value)
{
	if (strlen($value) == 0)
		return;

	if ($value < -1)
		echo 'bgcolor="Red"';
	elseif ($value < 0)
		echo 'bgcolor="Tomato"';
	elseif ($value < 1)
		echo 'bgcolor="orange"';
	elseif ($value < 3)
		echo 'bgcolor="lightgreen"';
	elseif ($value >= 3)
		echo 'bgcolor="GreenYellow"';
}

foreach ($accounts as $account)
{
	global $sum;

	$valueToUpdate = ($account->GetInvestmentLastValueDate() != '' && strtotime($account->GetInvestmentLastValueDate()) < strtotime("-".$account->get('minimumCheckPeriod')." days"));
	$openingYear = date("Y", strtotime($account->get('creationDate')));
	$openingDateToDisplay = ($account->get('creationDate') != '' && $account->get('creationDate') != '0000-00-00');
	$availabilityYear = date("Y", strtotime($account->get('availabilityDate')));
	$availabilityDateToDisplay = ($account->get('availabilityDate') != '' && $availabilityYear > date("Y"));
?>
<tr>
<td <?php if (!$account->get('noColorInDashboard')) { PrintTDStyleAttributeForAccountName($account->GetInvestmentLastYieldAverage(), $account->GetInvestmentLastYield()); } ?>><a href="#" onclick="javascript:ChangeContext('record','investment','<?= $account->get('accountId')?>',''); return false;"><?= $account->get('name') ?></a></td>
<td style='text-align: right;'><?= $valueToUpdate ? '<i>' : '' ?><?= $translator->getCurrencyValuePresentation($account->GetInvestmentLastValue()) ?><?= $valueToUpdate ? '</i>' : '' ?></td>
<td><?= $account->get('description') ?></td>
<td style='text-align: right;'><?= $openingDateToDisplay ? $openingYear : '' ?></td>
<td style='text-align: right;' <?php if (!$account->get('noColorInDashboard')) { PrintTDStyleAttributeForYield($account->GetInvestmentLastYield()); } ?>><?= $translator->getPercentagePresentation($account->GetInvestmentLastYield()) ?></td>
<td style='text-align: right;' <?php if (!$account->get('noColorInDashboard')) { PrintTDStyleAttributeForYieldAverage($account->GetInvestmentLastYieldAverage()); } ?>><?= $translator->getPercentagePresentation($account->GetInvestmentLastYieldAverage()) ?></td>
<td style='text-align: right;'><?= $availabilityDateToDisplay ? $availabilityYear : '' ?></td>
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

/******************** Shared Assets ********************/

$sum = 0;
$sumGlobal = 0;

$accounts = $accountsHandler->GetAllDuoAccounts();

foreach ($accounts as $account)
{
	$balance = $account->GetBalance();
	?>
	<tr>
		<td><a href="#" onclick="javascript:ChangeContext('record','','<?= $account->get('accountId')?>',''); return false;"><?= $account->get('name') ?></a></td>
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
	<td colspan="5"><b><?= $translator->getTranslation('Placements') ?></b></td>
</tr>
<?php
$sum = 0;
$accounts = $accountsHandler->GetAllSharedInvestmentAccounts();

foreach ($accounts as $account)
{
	?>
	<tr>
		<td><a href="#" onclick="javascript:ChangeContext('record','investment','<?= $account->get('accountId')?>',''); return false;"><?= $account->get('name') ?></a></td>
		<td style='text-align: right;'><?= $translator->getCurrencyValuePresentation($account->GetInvestmentLastValue()) ?></td>
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
	<td colspan="2"><b><?= $translator->getTranslation('Emprunts') ?></b></td>
</tr>
<?php
$sum = 0;
$accounts = $accountsHandler->GetAllSharedLoans();

foreach ($accounts as $account)
{
	$balance = $account->GetBalance();
	?>
	<tr>
		<td><a href="#" onclick="javascript:ChangeContext('record','','<?= $account->get('accountId')?>',''); return false;"><?= $account->get('name') ?></a></td>
		<td style='text-align: right;'><?= $translator->getCurrencyValuePresentation($balance) ?></td>
	</tr>
	<?php
	$sum += $balance;
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
</tr>
</table>