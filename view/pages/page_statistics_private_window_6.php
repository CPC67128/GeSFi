<h1><?= $translator->getTranslation('Tableau de bord privé, revenus sur 6 mois glissants') ?></h1>
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
$dateStart = new DateTime(date("Y-m-d", strtotime($dateEnd->format("Y-m-d") . " -6 months")));
$categories = $categoriesHandler->GetIncomeCategoriesForUser($activeUser->get('userId'));
foreach ($categories as $category)
{
	$value = $category->GetTotalIncomeBetween2Dates($dateStart, $dateEnd);
	$average = $value / 6;
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
?>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverage) ?></td>
</tr>
</tbody>
</table>
<small><font color="red">* </font> : <?= $translator->getTranslation('données non disponibles sur 6 mois complets') ?></small>

<br />

<h1><?= $translator->getTranslation('Tableau de bord privé, dépenses sur 6 mois glissants') ?></h1>
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
	$average = $value / 6;
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
?>
<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalAverage) ?></td>
</tr>
</tbody>
</table>
<small><font color="red">* </font> : <?= $translator->getTranslation('données non disponibles sur 6 mois complets') ?></small>