<h1><?= $translator->getTranslation('Dépenses (12 mois glissants)') ?></h1>
<table id="recordsTable">
<tbody>
<tr class="tableRowTitle">
<td><?= $translator->getTranslation('Revenu') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
</tr>
<?php
$dateEnd = new DateTime(date("Y").'-'.date("m").'-01');
$dateStart = new DateTime(date("Y-m-d", strtotime($dateEnd->format("Y-m-d") . " -12 months")));
$total = 0;
$index = 0;
$categories = $categoryHandler->GetIncomeCategoriesForUser($activeUser->get('userId'));
foreach ($categories as $category)
{
	$value = $category->GetTotalIncomeBetween2Dates($dateStart, $dateEnd);
	$total += $value;

	if ($value > 0)
	{
		?>
		<tr class="tableRow<?= (++$index) % 2 ?>">
		<td><?= $category->getCategory() ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($value) ?></td>
		</tr>
		<?php
	}
}
?>
<tr class="tableRowTitle">
<td><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
</tr>

</tbody>
</table>
<br />
<table id="recordsTable">
<tbody>
<tr class="tableRowTitle">
<td><?= $translator->getTranslation('Dépenses privées') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
</tr>
<?php
$total = 0;
$index = 0;
$categories = $categoryHandler->GetOutcomeCategoriesForUser($activeUser->get('userId'));
foreach ($categories as $category)
{
	$value = $category->GetTotalExpenseBetween2Dates($dateStart, $dateEnd);
	$total += $value;
	
	if ($value > 0)
	{
		?>
		<tr class="tableRow<?= (++$index) % 2 ?>">
		<td><?= $category->getCategory() ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($value) ?></td>
		</tr>
		<?php
	}
}
?>
<tr class="tableRowTitle">
<td><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
</tr>

</tbody>
</table>

</tbody>
</table>
<br />
<table id="recordsTable">
<tbody>
<tr class="tableRowTitle">
<td><?= $translator->getTranslation('Dépenses privées') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
</tr>
<?php
$total = 0;
$totalCharged = 0;
$index = 0;
$categories = $categoryHandler->GetOutcomeCategoriesForDuo($activeUser->get('userId'));
foreach ($categories as $category)
{
	$value = $category->GetTotalExpenseBetween2Dates($dateStart, $dateEnd);
	$valueCharged = $category->GetTotalExpenseChargedBetween2Dates($dateStart, $dateEnd);
	$total += $value;
	$totalCharged += $valueCharged;

	if ($value > 0)
	{
		?>
		<tr class="tableRow<?= (++$index) % 2 ?>">
		<td><?= $category->getCategory() ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($value) ?></td>
		<td class="amount"><?= ($category->get('activeFrom') > $dateStart->format("Y-m-d")) ? '<font color="red">* </font>' : '' ?><?= $translator->getCurrencyValuePresentation($valueCharged) ?></td>
		</tr>
		<?php
	}
}
?>
<tr class="tableRowTitle">
<td><?= $translator->getTranslation('Total revenus') ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
<td class="amount"><?= $translator->getCurrencyValuePresentation($totalCharged) ?></td>
</tr>

</tbody>
</table>

<small><font color="red">* </font> : <?= $translator->getTranslation('données non disponibles sur 12 mois') ?></small>
