<?php
$currentYear = Date('Y');
?>
<h1><?= $translator->getTranslation('Tableau de bord privé, année').' '.$currentYear ?></h1>
<table id="recordsTable">
<thead>
<tr class="tableRowTitle">
<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Mois') ?><br>
</td>
<?php
$currentMonth = 0;

for ($month = 12; $month >= 1; $month--)
{
	?>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $month ?><br>
	<td style="vertical-align: top; text-align: center; font-style: italic; border-right: solid 2px black;">/<?= $currentYear-1 ?><br>
	<?php
}
?>
<td style="vertical-align: top; text-align: center; font-style: italic;">Total<br>
<td style="vertical-align: top; text-align: center; font-style: italic; border-right: solid 2px black;">/<?= $currentYear-1 ?><br>
<td style="vertical-align: top; text-align: center; font-style: italic; white-space: nowrap;">Moyenne (/ 30 jours)<br>
</tr>
</thead>
<tbody>
<?php

$index = 0;
$currentMonth = 0;
$indexCategory = 0;
$totalAverage = 0;
$categories = $categoryHandler->GetIncomeCategoriesForUser($_SESSION['user_id']);

// Income
foreach ($categories as $category)
{
	$index++;
	echo '<tr class="tableRow';

	if ($index % 2 == 0)
		echo '0">';
	else
		echo '1">';

	echo '<td style="text-align: left; white-space: nowrap">';
	echo $category->get('category');
	echo '</td>';

	$totalY = 0;
	$totalYP = 0;

	for ($month = 12; $month >= 1; $month--)
	{
		$valueY = 0;
		$valueYP = 0;

		$valueY = $category->GetTotalIncomeByMonthAndYear($month, $currentYear);
		$valueYP = $category->GetTotalIncomeByMonthAndYear($month, $currentYear - 1);

		$monthYTotalIncome[$month] = (isset($monthYTotalIncome[$month]) ? $monthYTotalIncome[$month] : 0) + $valueY;
		$monthYPTotalIncome[$month] = (isset($monthYPTotalIncome[$month]) ? $monthYPTotalIncome[$month] : 0) + $valueYP;

		$totalY += $valueY;
		$totalYP += $valueYP;
		?>
		<td style="text-align: right; white-space: nowrap;"><?= ($valueY > 0) ? $translator->getCurrencyValuePresentation($valueY) : '&nbsp;' ?></td>
		<td style="text-align: right; white-space: nowrap; border-right: solid 2px black;"><?= ($valueYP > 0) ? $translator->getCurrencyValuePresentation($valueYP) : '&nbsp;' ?></td>
		<?php
	}

	?>
	<td style="text-align: right; white-space: nowrap;"><?= ($totalY > 0) ? $translator->getCurrencyValuePresentation($totalY) : '&nbsp;' ?></td>
	<td style="text-align: right; white-space: nowrap; border-right: solid 2px black;"><?= ($totalYP > 0) ? $translator->getCurrencyValuePresentation($totalYP) : '&nbsp;' ?></td>
	<?php
	echo '<td style="text-align: right;">';
	if ($totalY > 0)
	{
		$average = $category->GetAverageRevenueByMonth();
		$totalAverage += $average;
		echo $translator->getCurrencyValuePresentation($average);
	}
	echo '</td>';

	echo '</tr>';

	$indexCategory++;
}

echo '<tr class="tableRowTitle">';

echo '<td style="text-align: left;">'.$translator->getTranslation('Total revenus').'</td>';
$index = 0;
$currentMonth = 0;

$totalY = 0;
$totalYP = 0;

for ($month = 12; $month >= 1; $month--)
{
	$monthYTotalIncome[$month] = (isset($monthYTotalIncome[$month]) ? $monthYTotalIncome[$month] : 0);
	$monthYPTotalIncome[$month] = (isset($monthYPTotalIncome[$month]) ? $monthYPTotalIncome[$month] : 0);

	$totalY += $monthYTotalIncome[$month];
	$totalYP += $monthYPTotalIncome[$month];

	?>
	<td style="text-align: right; white-space: nowrap;"><?= ($monthYTotalIncome[$month] > 0) ? $translator->getCurrencyValuePresentation($monthYTotalIncome[$month]) : '' ?></td>
	<td style="text-align: right; white-space: nowrap; border-right: solid 2px black;"><?= ($monthYPTotalIncome[$month] > 0) ? $translator->getCurrencyValuePresentation($monthYPTotalIncome[$month]) : '&nbsp;' ?></td>
	<?php
}
?>
<td style="text-align: right; white-space: nowrap;"><?= ($totalY > 0) ? $translator->getCurrencyValuePresentation($totalY) : '' ?></td>
<td style="text-align: right; white-space: nowrap; border-right: solid 2px black;"><?= ($totalYP > 0) ? $translator->getCurrencyValuePresentation($totalYP) : '' ?></td>
<td style="text-align: right; white-space: nowrap;"><?= ($totalYP > 0) ? $translator->getCurrencyValuePresentation($totalAverage) : '&nbsp;' ?></td>
</tr>
<?php

$index = 0;
$currentMonth = 0;
$totalAverage = 0;
$categories = $categoryHandler->GetOutcomeCategoriesForUser($_SESSION['user_id']);


// Expense
foreach ($categories as $category)
{
	$index++;
	echo '<tr class="tableRow';

	if ($index % 2 == 0)
		echo '0">';
	else
		echo '1">';

	echo '<td style="text-align: left; white-space: nowrap">';
	echo $category->get('category');
	echo '</td>';

	$totalY = 0;
	$totalYP = 0;

	for ($month = 12; $month >= 1; $month--)
	{
		$valueY = 0;
		$valueYP = 0;

		$valueY = $category->GetTotalExpenseByMonthAndYear($month, $currentYear);
		$valueYP = $category->GetTotalExpenseByMonthAndYear($month, $currentYear - 1);

		$monthYTotalExpense[$month] = (isset($monthYTotalExpense[$month]) ? $monthYTotalExpense[$month] : 0) + $valueY;
		$monthYPTotalExpense[$month] = (isset($monthYPTotalExpense[$month]) ? $monthYPTotalExpense[$month] : 0) + $valueYP;

		$totalY += $valueY;
		$totalYP += $valueYP;
		?>
		<td style="text-align: right; white-space: nowrap;"><?= ($valueY > 0) ? $translator->getCurrencyValuePresentation($valueY) : '' ?></td>
		<td style="text-align: right; white-space: nowrap; border-right: solid 2px black;"><?= ($valueYP > 0) ? $translator->getCurrencyValuePresentation($valueYP) : '&nbsp;' ?></td>
		<?php
	}

	?>
	<td style="text-align: right; white-space: nowrap;"><?= ($totalY > 0) ? $translator->getCurrencyValuePresentation($totalY) : '' ?></td>
	<td style="text-align: right; white-space: nowrap; border-right: solid 2px black;"><?= ($totalYP > 0) ? $translator->getCurrencyValuePresentation($totalYP) : '&nbsp;' ?></td>
	<?php
	echo '<td style="text-align: right;">';
	if ($totalY > 0)
	{
		$average = $category->GetAverageExpenseByMonth();
		$totalAverage += $average;
		echo $translator->getCurrencyValuePresentation($average);
	}
	echo '</td>';

	echo '</tr>';

	$indexCategory++;
}

echo '<tr class="tableRowTitle">';

echo '<td style="text-align: left;">'.$translator->getTranslation('Total dépenses').'</td>';
$index = 0;
$currentMonth = 0;

$totalY = 0;
$totalYP = 0;

for ($month = 12; $month >= 1; $month--)
{
	$monthYTotalExpense[$month] = (isset($monthYTotalExpense[$month]) ? $monthYTotalExpense[$month] : 0);
	$monthYPTotalExpense[$month] = (isset($monthYPTotalExpense[$month]) ? $monthYPTotalExpense[$month] : 0);

	$totalY += $monthYTotalExpense[$month];
	$totalYP += $monthYPTotalExpense[$month];

	?>
	<td style="text-align: right; white-space: nowrap;"><?= ($monthYTotalExpense[$month] > 0) ? $translator->getCurrencyValuePresentation($monthYTotalExpense[$month]) : '' ?></td>
	<td style="text-align: right; white-space: nowrap; border-right: solid 2px black;"><?= ($monthYPTotalExpense[$month] > 0) ? $translator->getCurrencyValuePresentation($monthYPTotalExpense[$month]) : '&nbsp;' ?></td>
	<?php
}
?>
<td style="text-align: right; white-space: nowrap;"><?= ($totalY > 0) ? $translator->getCurrencyValuePresentation($totalY) : '' ?></td>
<td style="text-align: right; white-space: nowrap; border-right: solid 2px black;"><?= ($totalYP > 0) ? $translator->getCurrencyValuePresentation($totalYP) : '&nbsp;' ?></td>
<td style="text-align: right; white-space: nowrap;"><?= ($totalYP > 0) ? $translator->getCurrencyValuePresentation($totalAverage) : '&nbsp;' ?></td>
</tr>

<?php
// --------------------------------------- Duo

echo '<tr class="tableRow';

if ($index % 2 == 0)
	echo '0">';
else
	echo '1">';

echo '<td style="text-align: left; white-space: nowrap">';
echo 'Duo';
echo '</td>';

$total = 0;
for ($month = 12; $month >= 1; $month--)
{
	$valueY = 0;
	$valueYP = 0;

	$valueY = $recordsManager->GetTotalOutcomeToDuoAccount($month, $currentYear);
	$valueYP = $recordsManager->GetTotalOutcomeToDuoAccount($month, $currentYear - 1);

	$monthYTotalExpense[$month] = (isset($monthYTotalExpense[$month]) ? $monthYTotalExpense[$month] : 0) + $valueY;
	$monthYPTotalExpense[$month] = (isset($monthYPTotalExpense[$month]) ? $monthYPTotalExpense[$month] : 0) + $valueYP;

	$totalY += $valueY;
	$totalYP += $valueYP;

	?>
	<td style="text-align: right; white-space: nowrap;"><?= ($valueY > 0) ? $translator->getCurrencyValuePresentation($valueY) : '' ?></td>
	<td style="text-align: right; white-space: nowrap; border-right: solid 2px black;"><?= ($valueYP > 0) ? $translator->getCurrencyValuePresentation($valueYP) : '&nbsp;' ?></td>
	<?php
}
?>
<td style="text-align: right; white-space: nowrap;"><?= ($totalY > 0) ? $translator->getCurrencyValuePresentation($totalY) : '' ?></td>
<td style="text-align: right; white-space: nowrap; border-right: solid 2px black;"><?= ($totalYP > 0) ? $translator->getCurrencyValuePresentation($totalYP) : '&nbsp;' ?></td>
<td style="text-align: right;"></td>
</tr>

<?php

// ----------- Epargne

echo '<tr class="tableRowTitle">';

echo '<td style="text-align: left;">'.$translator->getTranslation('Epargne').'</td>';
$index = 0;
$currentMonth = 0;
$total = 0;
for ($month = 12; $month >= 1; $month--)
{
	$totalY += ($monthYTotalIncome[$month] - $monthYTotalExpense[$month]);
	$totalYP += ($monthYPTotalIncome[$month] - $monthYPTotalExpense[$month]);

	?>
	<td style="text-align: right; white-space: nowrap;"><?= $translator->getCurrencyValuePresentation($monthYTotalIncome[$month] - $monthYTotalExpense[$month]) ?></td>
	<td style="text-align: right; white-space: nowrap; border-right: solid 2px black;"><?= $translator->getCurrencyValuePresentation($monthYPTotalIncome[$month] - $monthYPTotalExpense[$month]) ?></td>
	<?php
}
?>
<td style="text-align: right; white-space: nowrap;"><?= ($totalY > 0) ? $translator->getCurrencyValuePresentation($totalY) : '' ?></td>
<td style="text-align: right; white-space: nowrap; border-right: solid 2px black;"><?= ($totalYP > 0) ? $translator->getCurrencyValuePresentation($totalYP) : '&nbsp;' ?></td>
<td style="text-align: right;"></td>
</tr>

</tbody>
</table>