<h1><?= $translator->getTranslation('Tableau de bord duo, revenus et dépenses') ?></h1>
<table class="statsTable">
<tbody>

<?php /* =============== Global header =============== */ ?>

<tr class="statsTableRowTitle">
<td class="statsTableRowHeader"><?= $translator->getTranslation('Mois') ?></td>
<?php
$currentMonth = 0;

for ($month = 0; $month < 24; $month++)
{
	$monthTotalIncome[$month] = 0;
	$monthTotalExpense[$month] = 0;

	$old_current_month = $currentMonth;
	$currentMonth = Date('m', strtotime("-".$month." month"));
	if ($old_current_month == $currentMonth) // management of PHP bug on 31st of a month
	{
		$currentMonth = Date('m', strtotime("-10 days -".$month." month"));
		$currentYear = Date('Y', strtotime("-10 days -".$month." month"));
	}
	else
	{
		$currentYear = Date('Y', strtotime("-".$month." month"));
	}
	
	?>
	<td><?= $translator->getMonthYearPresentation($currentMonth, $currentYear) ?></td>
	<?php
}
?>
</tr>

<?php /* =============== Income detail =============== */ ?>

<?php

$index = 0;
$currentMonth = 0;
$indexCategory = 0;
$totalAverage = 0;
$categories = $categoriesHandler->GetIncomeCategoriesForDuo($_SESSION['user_id']);

// Income
foreach ($categories as $category)
{
	$index++;
	?>
	<tr class="statsTableRow<?= $index % 2 == 0 ? '0' : '1' ?>">
	<td class="statsTableRowHeader"><?= $category->get('category') ?></td>
	<?php
	$total = 0;
	for ($month = 0; $month < 24; $month++)
	{
		$old_current_month = $currentMonth;
		$currentMonth = Date('m', strtotime("-".$month." month"));
		if ($old_current_month == $currentMonth) // management of PHP bug on 31st of a month
		{
			$currentMonth = Date('m', strtotime("-10 days -".$month." month"));
			$currentYear = Date('Y', strtotime("-10 days -".$month." month"));
		}
		else
		{
			$currentYear = Date('Y', strtotime("-".$month." month"));
		}
		
		$value = 0;
		$value = $category->GetTotalIncomeByMonthAndYear($currentMonth, $currentYear);
		$monthTotalIncome[$month] = (isset($monthTotalIncome[$month]) ? $monthTotalIncome[$month] : 0) + $value;
		$total += $value;
		?>
		<td><?= ($value > 0) ? $translator->getCurrencyValuePresentation($value) : '' ?></td>
		<?php
	}

	?>
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

for ($month = 0; $month < 24; $month++)
{
	$monthTotalIncome[$month] = (isset($monthTotalIncome[$month]) ? $monthTotalIncome[$month] : 0);
	$total += $monthTotalIncome[$month];
	?>
	<td class="amount"><?= ($monthTotalIncome[$month] > 0) ? $translator->getCurrencyValuePresentation($monthTotalIncome[$month]) : '' ?></td>
	<?php
}
?>
</tr>


<?php /* =============== Expense detail =============== */ ?>

<?php
$index = 0;
$currentMonth = 0;
$indexCategory = 0;
$totalAverage = 0;
$categories = $categoriesHandler->GetOutcomeCategoriesForDuo($_SESSION['user_id']);

foreach ($categories as $category)
{
	$index++;
	?>
	<tr class="statsTableRow<?= $index % 2 == 0 ? '0' : '1' ?>">
	<td class="statsTableRowHeader"><?= $category->get('category') ?></td>
	<?php
	$total = 0;
	for ($month = 0; $month < 24; $month++)
	{
		$old_current_month = $currentMonth;
		$currentMonth = Date('m', strtotime("-".$month." month"));
		if ($old_current_month == $currentMonth) // management of PHP bug on 31st of a month
		{
			$currentMonth = Date('m', strtotime("-10 days -".$month." month"));
			$currentYear = Date('Y', strtotime("-10 days -".$month." month"));
		}
		else
		{
			$currentYear = Date('Y', strtotime("-".$month." month"));
		}

		$value = 0;
		$value = $category->GetTotalExpenseByMonthAndYear($currentMonth, $currentYear);
		$monthTotalExpense[$month] = (isset($monthTotalExpense[$month]) ? $monthTotalExpense[$month] : 0) + $value;
		$total += $value;
		?>
		<td><?= ($value > 0) ? $translator->getCurrencyValuePresentation($value) : '' ?></td>
		<?php
	}

	?>
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

for ($month = 0; $month < 24; $month++)
{
	$monthTotalExpense[$month] = (isset($monthTotalExpense[$month]) ? $monthTotalExpense[$month] : 0);
	$total += $monthTotalExpense[$month];
	?>
	<td class="amount"><?= ($monthTotalExpense[$month] > 0) ? $translator->getCurrencyValuePresentation($monthTotalExpense[$month]) : '' ?></td>
	<?php
}
?>
</tr>

</tbody>
</table>