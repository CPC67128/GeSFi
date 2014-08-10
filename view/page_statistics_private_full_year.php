<h1><?= $translator->getTranslation('Tableau de bord privé, année').' '.$currentYear ?></h1>
<table class="statsTable">
<tbody>

<?php /* =============== Global header =============== */ ?>

<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Mois') ?></td>
<?php
$currentMonth = 0;

for ($month = 1; $month <= 12; $month++)
{
	$monthTotalIncome[$month] = 0;
	$monthTotalExpense[$month] = 0;
	?>
	<td><?= $month ?></td>
	<?php
}
?>
<td class="statsTableRowSummary"><?= $translator->getTranslation('Total') ?></td>
<td><?= $translator->getTranslation('Moyenne') ?></td>
</tr>

<?php /* =============== Income detail =============== */ ?>

<?php

$index = 0;
$currentMonth = 0;
$indexCategory = 0;
$totalAverage = 0;
$categories = $categoriesHandler->GetIncomeCategoriesForUser($_SESSION['user_id']);

// Income
foreach ($categories as $category)
{
	$index++;
	?>
	<tr class="statsTableRow<?= $index % 2 == 0 ? '0' : '1' ?>">
	<td class="statsTableRowHeader"><?= $category->get('category') ?></td>
	<?php
	$total = 0;
	for ($month = 1; $month <= 12; $month++)
	{
		$value = 0;
		$value = $category->GetTotalIncomeByMonthAndYear($month, $currentYear);
		$monthTotalIncome[$month] = (isset($monthTotalIncome[$month]) ? $monthTotalIncome[$month] : 0) + $value;
		$total += $value;
		?>
		<td><?= ($value > 0) ? $translator->getCurrencyValuePresentation($value) : '' ?></td>
		<?php
	}

	?>
	<td class="statsTableRowSummary"><?= ($total > 0) ? $translator->getCurrencyValuePresentation($total) : '&nbsp;' ?></td>
	<td>
	<?php
	if ($total > 0)
	{
		$average = $category->GetAverageRevenueByMonth();
		$totalAverage += $average;
		echo $translator->getCurrencyValuePresentation($average);
	}
	?>
	</td>
	</tr>

	<?php
	$indexCategory++;
}
?>

<?php /* =============== Income summary =============== */ ?>

<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Total revenus') ?></td>
<?php
$currentMonth = 0;
$total = 0;

for ($month = 1; $month <= 12; $month++)
{
	$monthTotalIncome[$month] = (isset($monthTotalIncome[$month]) ? $monthTotalIncome[$month] : 0);
	$total += $monthTotalIncome[$month];
	?>
	<td class="amount"><?= ($monthTotalIncome[$month] > 0) ? $translator->getCurrencyValuePresentation($monthTotalIncome[$month]) : '' ?></td>
	<?php
}
?>
<td class="statsTableRowSummary amount"><?= ($total > 0) ? $translator->getCurrencyValuePresentation($total) : '' ?></td>
<td class="amount"><?= ($total > 0) ? $translator->getCurrencyValuePresentation($totalAverage) : '&nbsp;' ?></td>
</tr>


<?php /* =============== Expense detail =============== */ ?>

<?php
$index = 0;
$currentMonth = 0;
$indexCategory = 0;
$totalAverage = 0;
$categories = $categoriesHandler->GetOutcomeCategoriesForUser($_SESSION['user_id']);

foreach ($categories as $category)
{
	$index++;
	?>
	<tr class="statsTableRow<?= $index % 2 == 0 ? '0' : '1' ?>">
	<td class="statsTableRowHeader"><?= $category->get('category') ?></td>
	<?php
	$total = 0;
	for ($month = 1; $month <= 12; $month++)
	{
		$value = 0;
		$value = $category->GetTotalExpenseByMonthAndYear($month, $currentYear);
		$monthTotalExpense[$month] = (isset($monthTotalExpense[$month]) ? $monthTotalExpense[$month] : 0) + $value;
		$total += $value;
		?>
		<td><?= ($value > 0) ? $translator->getCurrencyValuePresentation($value) : '' ?></td>
		<?php
	}

	?>
	<td class="statsTableRowSummary"><?= ($total > 0) ? $translator->getCurrencyValuePresentation($total) : '&nbsp;' ?></td>
	<td>
	<?php
	if ($total > 0)
	{
		$average = $category->GetAverageExpenseByMonth();
		$totalAverage += $average;
		echo $translator->getCurrencyValuePresentation($average);
	}
	?>
	</td>
	</tr>

	<?php
	$indexCategory++;
}
?>

<?php /* =============== Expense summary =============== */ ?>

<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Total dépenses') ?></td>
<?php
$currentMonth = 0;
$total = 0;

for ($month = 1; $month <= 12; $month++)
{
	$monthTotalExpense[$month] = (isset($monthTotalExpense[$month]) ? $monthTotalExpense[$month] : 0);
	$total += $monthTotalExpense[$month];
	?>
	<td class="amount"><?= ($monthTotalExpense[$month] > 0) ? $translator->getCurrencyValuePresentation($monthTotalExpense[$month]) : '' ?></td>
	<?php
}
?>
<td class="statsTableRowSummary amount"><?= ($total > 0) ? $translator->getCurrencyValuePresentation($total) : '' ?></td>
<td class="amount"><?= ($total > 0) ? $translator->getCurrencyValuePresentation($totalAverage) : '&nbsp;' ?></td>
</tr>

<?php /* =============== Duo summary =============== */ ?>

<tr class="statsTableRow0">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Duo') ?></td>
<?php
$total = 0;

for ($month = 1; $month <= 12; $month++)
{
	$value = 0;
	$value = $recordsHandler->GetTotalOutcomeToDuoAccount($month, $currentYear) - $recordsHandler->GetTotalIncomeFromDuoAccount($month, $currentYear);
	$monthTotalExpense[$month] = (isset($monthTotalExpense[$month]) ? $monthTotalExpense[$month] : 0) + $value;
	$total += $value;

	?>
	<td><?= ($value > 0) ? $translator->getCurrencyValuePresentation($value) : '' ?></td>
	<?php
}
?>
<td class="statsTableRowSummary"><?= ($total > 0) ? $translator->getCurrencyValuePresentation($total) : '&nbsp;' ?></td>
<td></td>
</tr>

<?php /* =============== Saving summary =============== */ ?>

<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Epargne') ?></td>
<?php
$currentMonth = 0;
$total = 0;

for ($month = 1; $month <= 12; $month++)
{
	$total += ($monthTotalIncome[$month] - $monthTotalExpense[$month]);
	?>
	<td class="amount"><?= $translator->getCurrencyValuePresentation($monthTotalIncome[$month] - $monthTotalExpense[$month]) ?></td>
	<?php
}
?>
<td class="statsTableRowSummary amount"><?= $translator->getCurrencyValuePresentation($total) ?></td>
<td></td>
</tr>

</tbody>
</table>