<?php
$categoryHandler = new CategoryHandler();
$categories = $categoryHandler->GetOutcomeCategoriesForDuo($_SESSION['user_id']);
?>
<h1><?= $translator->getTranslation('Dépenses par catégories duo') ?></h1>
<table id="recordsTable">
<thead>
<tr class="tableRowTitle">
<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Mois') ?><br>
</td>
<?php
foreach ($categories as $category)
{
	?>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $category->getCategory() ?><br>
	<?php
}
?>
<td style="vertical-align: top; text-align: center; font-style: italic;">Total<br>
</tr>
</thead>
<tbody>
<?php

$index = 0;
$currentMonth = 0;
for ($month = 0; $month < 20; $month++)
{
	$index++;
	echo '<tr class="tableRow';

	if ($index % 2 == 0)
		echo '0">';
	else
		echo '1">';

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

	echo '<td style="text-align: right; white-space: nowrap;">';
	echo $translator->getMonthYearPresentation($currentMonth, $currentYear);
	echo '</td>';

	$total = 0;
	$indexCategory = 0;
	foreach ($categories as $category)
	{
		$value = $category->GetTotalExpenseByMonthAndYear($currentMonth, $currentYear);
		if (!isset($category_total[$indexCategory]))
			$category_total[$indexCategory] = 0;
		$category_total[$indexCategory] = $category_total[$indexCategory] + $value;
		$total += $value;

		echo '<td style="text-align: right; white-space: nowrap;">';
		if ($value > 0)
		{
			if (!isset($category_non_empty_months[$indexCategory]))
				$category_non_empty_months[$indexCategory] = 0;
			$category_non_empty_months[$indexCategory] = $category_non_empty_months[$indexCategory] + 1;
			echo $translator->getCurrencyValuePresentation($value);
		}
		echo '</td>';

		$indexCategory++;
	}

	echo '<td style="text-align: right; white-space: nowrap;">';
	if ($total > 0)
	{
		if (!isset($category_total[$indexCategory]))
			$category_total[$indexCategory] = 0;
		$category_total[$indexCategory] = $category_total[$indexCategory] + $total;
		if (!isset($category_non_empty_months[$indexCategory]))
			$category_non_empty_months[$indexCategory] = 0;
		$category_non_empty_months[$indexCategory] = $category_non_empty_months[$indexCategory] + 1;
		echo $translator->getCurrencyValuePresentation($total);
	}
	echo '</td>';

	echo '</tr>';
}

echo '<tr class="tableRowTitle">';

echo '<td style="text-align: right;">Moyenne</td>';
$index = 0;
foreach ($categories as $category)
{
	echo '<td style="text-align: right; white-space: nowrap;">';
	if (isset($category_non_empty_months[$index]) && $category_non_empty_months[$index] > 0)
		echo $translator->getCurrencyValuePresentation($category_total[$index] / $category_non_empty_months[$index]);
	echo '</td>';
	$index++;
}
echo '<td style="text-align: right; white-space: nowrap;">';
if (isset($category_non_empty_months[$index]) && $category_non_empty_months[$index] > 0)
	echo $translator->getCurrencyValuePresentation($category_total[$index] / $category_non_empty_months[$index]);
echo '</td>';
echo '</tr>';

echo '<tr class="tableRowTitle">';

echo '<td style="text-align: right; white-space: nowrap;">(Cumul)</td>';
$total = 0;
foreach ($categories as $category)
{
	echo '<td style="text-align: right; white-space: nowrap;">';
	if (isset($category_non_empty_months[$index]) && $category_non_empty_months[$index] > 0)
		$total = $total + ($category_total[$index] / $category_non_empty_months[$index]);
	echo $translator->getCurrencyValuePresentation($total);
	echo '</td>';
}
echo '<td style="text-align: right; white-space: nowrap;">';
echo '</td>';
echo '</tr>';
?>
</tbody>
</table>