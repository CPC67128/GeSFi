<h1><?= $translator->getTranslation('Tableau de bord privé, revenus sur 12 mois glissants') ?></h1>
<table class="statsTable">
<tbody>
<tr class="statsTableRowTitle">
<td><?= $translator->getTranslation('Revenu') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
</tr>
<?php
$total = 0;
$index = 0;

$dateEnd = new DateTime(date("Y").'-'.date("m").'-01');
$dateStart = new DateTime(date("Y-m-d", strtotime($dateEnd->format("Y-m-d") . " -12 months")));
$categories = $categoriesHandler->GetIncomeCategoriesForUser($activeUser->get('userId'));
foreach ($categories as $category)
{
	$value = $category->GetTotalIncomeBetween2Dates($dateStart, $dateEnd);
	$total += $value;

	if ($value > 0)
	{
		?>
		<tr class="statsTableRow<?= (++$index) % 2 ?>">
		<td><?= $category->get('category') ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($value) ?></td>
		</tr>
		<?php
	}
}
?>
<tr class="statsTableRowTitle">
<td><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
</tr>
</tbody>
</table>
<small><font color="red">* </font> : <?= $translator->getTranslation('données non disponibles sur 12 mois complets') ?></small>

<br />

<h1><?= $translator->getTranslation('Tableau de bord privé, dépenses sur 12 mois glissants') ?></h1>
<table class="statsTable">
<tbody>
<tr class="statsTableRowTitle">
<td><?= $translator->getTranslation('Dépenses') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
</tr>
<?php
$total = 0;
$index = 0;
$categories = $categoriesHandler->GetOutcomeCategoriesForUser($activeUser->get('userId'));
foreach ($categories as $category)
{
	$value = $category->GetTotalExpenseBetween2Dates($dateStart, $dateEnd);
	$total += $value;
	
	if ($value > 0)
	{
		?>
		<tr class="statsTableRow<?= (++$index) % 2 ?>">
		<td><?= $category->get('category') ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($value) ?></td>
		</tr>
		<?php
	}
}
?>
<tr class="statsTableRowTitle">
<td><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
</tr>
</tbody>
</table>
<small><font color="red">* </font> : <?= $translator->getTranslation('données non disponibles sur 12 mois complets') ?></small>