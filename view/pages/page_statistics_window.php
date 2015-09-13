<?php
$nbMonths = 3;
if (isset($data))
	$nbMonths = $data;
?>

<small><font color="red">* </font> : <?= $translator->getTranslation('données non disponibles sur '.$nbMonths.' mois complets') ?></small>

<table class="blankTable">

<thead>
<th>Privé</th>
<th>Duo</th>
<th>Global</th>
</thead>

<tr>

<td>
<h1><?= $translator->getTranslation('Revenus privés sur '.$nbMonths.' mois glissants') ?></h1>
<table class="statsTable">
<tbody>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Revenu') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
<td><?= $translator->getTranslation('Moyenne') ?></td>
</tr>
<?php
$total = 0;
$average = 0;
$totalAverage = 0;
$index = 0;

$dateEnd = new DateTime(date("Y").'-'.date("m").'-01');
$dateStart = new DateTime(date("Y-m-d", strtotime($dateEnd->format("Y-m-d") . " -".$nbMonths." months")));
$categories = $categoriesHandler->GetIncomeCategoriesForUser($activeUser->get('userId'));
foreach ($categories as $category)
{
	$value = $category->GetTotalIncomeBetween2Dates($dateStart, $dateEnd);
	$average = $value / $nbMonths;
	$total += $value;
	$totalAverage += $average;

	if ($value > 0)
	{
		?>
		<tr class="statsTableRow<?= (++$index) % 2 ?>">
		<td class="statsTableRowHeader"><?= $category->get('category') ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($value) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($average) ?></td>
		</tr>
		<?php
	}
}

$totalPrivateDeposit = $total;
$totalAveragePrivateDeposit = $totalAverage;
?>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverage) ?></td>
</tr>
</tbody>
</table>
</td>

<!-- Duo -->
<td>
<h1><?= $translator->getTranslation('Revenus duo sur '.$nbMonths.' mois glissants') ?></h1>
<table class="statsTable">
<tbody>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Revenus') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
<td><?= $translator->getTranslation('Moyenne') ?></td>
<td><?= $translator->getTranslation('(pris en charge)') ?></td>
<td><?= $translator->getTranslation('Moyenne') ?></td>
</tr>
<?php
$total = 0;
$totalCharged = 0;
$average = 0;
$totalAverage = 0;
$averageCharged = 0;
$totalAverageCharged = 0;
$index = 0;
$categories = $categoriesHandler->GetIncomeCategoriesForDuo($activeUser->get('userId'));
foreach ($categories as $category)
{
	$value = $category->GetTotalIncomeBetween2Dates($dateStart, $dateEnd);
	$valueCharged = $category->GetTotalIncomeChargedBetween2Dates($dateStart, $dateEnd);
	$total += $value;
	$totalCharged += $valueCharged;

	$average = $value / $nbMonths;
	$totalAverage += $average;
	$averageCharged = $valueCharged / $nbMonths;
	$totalAverageCharged += $averageCharged;

	if ($value > 0)
	{
		?>
		<tr class="statsTableRow<?= (++$index) % 2 ?>">
		<td class="statsTableRowHeader"><?= $category->get('category') ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($value) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($average) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($valueCharged) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($averageCharged) ?></td>
		</tr>
		<?php
	}
}

$totalDuoDeposit = $totalCharged;
$totalAverageDuoDeposit = $totalAverageCharged;
?>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverage) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalCharged) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverageCharged) ?></td>
</tr>
</tbody>
</table>
</td>

<!-- Global -->
<td>
<h1><?= $translator->getTranslation('Revenus sur '.$nbMonths.' mois glissants') ?></h1>
<table class="statsTable">
<tbody>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Revenus') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
<td><?= $translator->getTranslation('Moyenne') ?></td>
</tr>
<?php
$total = 0;
$average = 0;
$totalAverage = 0;
$index = 0;

$totalDeposit = $totalPrivateDeposit + $totalDuoDeposit;
$totalAverageDeposit = $totalAveragePrivateDeposit + $totalAverageDuoDeposit;
?>
<tr class="statsTableRow<?= (++$index) % 2 ?>">
<td class="statsTableRowHeader">Privés</td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalPrivateDeposit) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAveragePrivateDeposit) ?></td>
</tr>
<tr class="statsTableRow<?= (++$index) % 2 ?>">
<td class="statsTableRowHeader">Duo</td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalDuoDeposit) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverageDuoDeposit) ?></td>
</tr>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Total') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalDeposit) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverageDeposit) ?></td>
</tr>
</tbody>
</table>
</td>


</tr>

<!-- --------------------------------------------------------------------------------------------- -->

<tr>
<td>

<h1><?= $translator->getTranslation('Dépenses privées sur '.$nbMonths.' mois glissants') ?></h1>
<table class="statsTable">
<tbody>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Dépenses') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
<td><?= $translator->getTranslation('Moyenne') ?></td>
</tr>
<?php
$total = 0;
$average = 0;
$totalAverage = 0;
$index = 0;
$categories = $categoriesHandler->GetOutcomeCategoriesForUser($activeUser->get('userId'));
foreach ($categories as $category)
{
	$value = $category->GetTotalExpenseBetween2Dates($dateStart, $dateEnd);
	$average = $value / $nbMonths;
	$total += $value;
	$totalAverage += $average;
	
	if ($value > 0)
	{
		?>
		<tr class="statsTableRow<?= (++$index) % 2 ?>">
		<td class="statsTableRowHeader"><?= $category->get('category') ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($value) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($average) ?></td>
		</tr>
		<?php
	}
}
$totalPrivatePayment = $total;
$totalAveragePrivatePayment = $totalAverage;
?>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverage) ?></td>
</tr>
</tbody>
</table>

</td>

<td>

<h1><?= $translator->getTranslation('Dépenses duo sur '.$nbMonths.' mois glissants') ?></h1>
<table class="statsTable">
<tbody>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Dépenses') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
<td><?= $translator->getTranslation('Moyenne') ?></td>
<td><?= $translator->getTranslation('(pris en charge)') ?></td>
<td><?= $translator->getTranslation('Moyenne') ?></td>
</tr>
<?php
$total = 0;
$totalCharged = 0;
$average = 0;
$totalAverage = 0;
$averageCharged = 0;
$totalAverageCharged = 0;
$index = 0;
$categories = $categoriesHandler->GetOutcomeCategoriesForDuo($activeUser->get('userId'));
foreach ($categories as $category)
{
	$value = $category->GetTotalExpenseBetween2Dates($dateStart, $dateEnd);
	$valueCharged = $category->GetTotalExpenseChargedBetween2Dates($dateStart, $dateEnd);
	$total += $value;
	$totalCharged += $valueCharged;

	$average = $value / $nbMonths;
	$totalAverage += $average;
	$averageCharged = $valueCharged / $nbMonths;
	$totalAverageCharged += $averageCharged;
	
	if ($value > 0)
	{
		?>
		<tr class="statsTableRow<?= (++$index) % 2 ?>">
		<td class="statsTableRowHeader"><?= $category->get('category') ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($value) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($average) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($valueCharged) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($averageCharged) ?></td>
		</tr>
		<?php
	}
}
$totalDuoPayment = $totalCharged;
$totalAverageDuoPayment = $totalAverageCharged;
?>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverage) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalCharged) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverageCharged) ?></td>
</tr>
</tbody>
</table>

</td>


<!-- Global -->
<td>
<h1><?= $translator->getTranslation('Dépenses sur '.$nbMonths.' mois glissants') ?></h1>
<table class="statsTable">
<tbody>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Dépenses') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
<td><?= $translator->getTranslation('Moyenne') ?></td>
</tr>
<?php
$total = 0;
$average = 0;
$totalAverage = 0;
$index = 0;

$totalPayment = $totalPrivatePayment + $totalDuoPayment;
$totalAveragePayment = $totalAveragePrivatePayment + $totalAverageDuoPayment;
?>
<tr class="statsTableRow<?= (++$index) % 2 ?>">
<td class="statsTableRowHeader">Privés</td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalPrivatePayment) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAveragePrivatePayment) ?></td>
</tr>
<tr class="statsTableRow<?= (++$index) % 2 ?>">
<td class="statsTableRowHeader">Duo</td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalDuoPayment) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverageDuoPayment) ?></td>
</tr>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Total') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalPayment) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAveragePayment) ?></td>
</tr>
</tbody>
</table>
</td>

</tr>

<!-- --------------------------------------------------------------------------------------------- -->

<tr>
<td></td>
<td></td>

<!-- Global -->
<td><h1><?= $translator->getTranslation('Capacité d\'épargne sur '.$nbMonths.' mois glissants') ?></h1>
<table class="statsTable">
<tbody>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Type') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
<td><?= $translator->getTranslation('Moyenne') ?></td>
</tr>
<?php
$total = 0;
$average = 0;
$totalAverage = 0;
$index = 0;

$total = $totalDeposit - $totalPayment;
$totalAverage = $totalAverageDeposit - $totalAveragePayment;
?>
<tr class="statsTableRow<?= (++$index) % 2 ?>">
<td class="statsTableRowHeader">Revenus</td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalDeposit) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverageDeposit) ?></td>
</tr>
<tr class="statsTableRow<?= (++$index) % 2 ?>">
<td class="statsTableRowHeader">Dépenses</td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalPayment) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAveragePayment) ?></td>
</tr>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Total') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverage) ?></td>
</tr>
</tbody>
</table>

</td>

</table>

