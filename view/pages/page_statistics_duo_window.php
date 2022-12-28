<?php
$nbMonths = 12;
if (isset($data))
	$nbMonths = $data;
?>
<?php
$dateEnd = new DateTime(date("Y").'-'.date("m").'-01');
$dateStart = new DateTime(date("Y-m-d", strtotime($dateEnd->format("Y-m-d") . " -".$nbMonths." months")));
?>
<h1><?= $translator->getTranslation('Tableau de bord duo, revenus sur '.$nbMonths.' mois glissants') ?></h1>
<table id="recordsTable">
<tbody>
<tr class="tableRowTitle">
<td><?= $translator->getTranslation('Revenus') ?></td>
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
$categories = $categoriesHandler->GetIncomeCategoriesForDuo($activeUser->get('userId'), false);
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
		<tr class="tableRow<?= (++$index) % 2 ?>">
		<td><?= $category->get('category') ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($value) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($average) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($valueCharged) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($averageCharged) ?></td>
		</tr>
		<?php
	}
}
?>
<tr class="tableRowTitle">
<td><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverage) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalCharged) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverageCharged) ?></td>
</tr>
</tbody>
</table>
<small><font color="red">* </font> : <?= $translator->getTranslation('données non disponibles sur '.$nbMonths.' mois complets') ?></small>

<?php
$dateEnd = new DateTime(date("Y").'-'.date("m").'-01');
$dateStart = new DateTime(date("Y-m-d", strtotime($dateEnd->format("Y-m-d") . " -".$nbMonths." months")));
?>
<h1><?= $translator->getTranslation('Tableau de bord duo, dépenses sur '.$nbMonths.' mois glissants') ?></h1>
<table id="recordsTable">
<tbody>
<tr class="tableRowTitle">
<td><?= $translator->getTranslation('Dépenses') ?></td>
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
$categories = $categoriesHandler->GetOutcomeCategoriesForDuo($activeUser->get('userId'), false);
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
		<tr class="tableRow<?= (++$index) % 2 ?>">
		<td><?= $category->get('category') ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($value) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($average) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($valueCharged) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($averageCharged) ?></td>
		</tr>
		<?php
	}
}
?>
<tr class="tableRowTitle">
<td><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverage) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalCharged) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverageCharged) ?></td>
</tr>
</tbody>
</table>
<small><font color="red">* </font> : <?= $translator->getTranslation('données non disponibles sur '.$nbMonths.' mois complets') ?></small>