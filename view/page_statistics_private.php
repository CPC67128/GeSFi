<h1>Statistiques par catégories privées</h1>
<table id="recordsTable">
<thead>
<tr class="tableRowTitle">
<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Mois') ?><br>
</td>
<?php

$currentMonth = 0;
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

	?>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getMonthYearPresentation($currentMonth, $currentYear); ?><br>
	<?php
}
?>
<td style="vertical-align: top; text-align: center; font-style: italic;">Total<br>
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
foreach ($categories as $category)
{
	$index++;
	echo '<tr class="tableRow';

	if ($index % 2 == 0)
		echo '0">';
	else
		echo '1">';

	echo '<td style="text-align: left; white-space: nowrap">';
	echo $category->getCategory();
	echo '</td>';

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

		$value = $category->GetTotalIncomeByMonthAndYear($currentMonth, $currentYear);

		if (!isset($monthTotalIncome[$month]))
			$monthTotalIncome[$month] = 0;
		$monthTotalIncome[$month] = $monthTotalIncome[$month] + $value;

		$total += $value;

		echo '<td style="text-align: right;">';
		if ($value > 0)
		{
			echo $translator->getCurrencyValuePresentation($value);
		}
		echo '</td>';
	}

	echo '<td style="text-align: right;">';
	if ($total > 0)
	{
		echo $translator->getCurrencyValuePresentation($total);
	}
	echo '</td>';

	echo '<td style="text-align: right;">';
	if ($total > 0)
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

	echo '<td style="text-align: right;">';
	if (!isset($monthTotalIncome[$month]))
		$monthTotalIncome[$month] = 0;
	$total += $monthTotalIncome[$month];
	echo $translator->getCurrencyValuePresentation($monthTotalIncome[$month]);
	echo '</td>';
}
echo '<td style="text-align: right;">';
echo $translator->getCurrencyValuePresentation($total);
echo '</td>';
echo '<td style="text-align: right;">';
echo $translator->getCurrencyValuePresentation($totalAverage);
echo '</td>';
echo '</tr>';

$index = 0;
$currentMonth = 0;
$totalAverage = 0;
$categories = $categoryHandler->GetOutcomeCategoriesForUser($_SESSION['user_id']);
foreach ($categories as $category)
{
	$index++;
	echo '<tr class="tableRow';

	if ($index % 2 == 0)
		echo '0">';
	else
		echo '1">';

	echo '<td style="text-align: left; white-space: nowrap">';
	echo $category->getCategory();
	echo '</td>';

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

		$value = $category->GetTotalExpenseByMonthAndYear($currentMonth, $currentYear);

		if (!isset($monthTotalExpense[$month]))
			$monthTotalExpense[$month] = 0;
		$monthTotalExpense[$month] = $monthTotalExpense[$month] + $value;

		$total += $value;

		echo '<td style="text-align: right;">';
		if ($value > 0)
		{
			echo $translator->getCurrencyValuePresentation($value);
		}
		echo '</td>';
	}
	
	echo '<td style="text-align: right;">';
	if ($total > 0)
	{
		echo $translator->getCurrencyValuePresentation($total);
	}
	echo '</td>';
	
	echo '<td style="text-align: right;">';
	if ($total > 0)
	{
		$average = $category->GetAverageExpenseByMonth();
		$totalAverage += $average;
		echo $translator->getCurrencyValuePresentation($average);
	}
	echo '</td>';

	echo '</tr>';
}

echo '<tr class="tableRowTitle">';

echo '<td style="text-align: left;">'.$translator->getTranslation('Total dépenses').'</td>';
$index = 0;
$currentMonth = 0;
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

	echo '<td style="text-align: right;">';
	if (!isset($monthTotalExpense[$month]))
		$monthTotalExpense[$month] = 0;
	$total += $monthTotalExpense[$month];
	echo $translator->getCurrencyValuePresentation($monthTotalExpense[$month]);
	echo '</td>';
}
echo '<td style="text-align: right;">';
echo $translator->getCurrencyValuePresentation($total);
echo '</td>';
echo '<td style="text-align: right;">';
echo $translator->getCurrencyValuePresentation($totalAverage);
echo '</td>';
echo '</tr>';

// ----------- Duo

echo '<tr class="tableRow';

if ($index % 2 == 0)
	echo '0">';
else
	echo '1">';

echo '<td style="text-align: left; white-space: nowrap">';
echo 'Duo';
echo '</td>';

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

	$value = $recordsManager->GetTotalOutcomeToDuoAccount($currentMonth, $currentYear);
	
	if (!isset($monthTotalExpense[$month]))
		$monthTotalExpense[$month] = 0;
	$monthTotalExpense[$month] = $monthTotalExpense[$month] + $value;

	$total += $value;

	echo '<td style="text-align: right;">';
	if ($value > 0)
	{
		echo $translator->getCurrencyValuePresentation($value);
	}
	echo '</td>';
}

echo '<td style="text-align: right;">';
if ($total > 0)
{
	echo $translator->getCurrencyValuePresentation($total);
}
echo '</td>';

echo '<td style="text-align: right;">';
if ($total > 0)
{
	if (!isset($category))
		$average = 0;
	else
		$average = $category->GetAverageExpenseByMonth();
	$totalAverage += $average;
	echo $translator->getCurrencyValuePresentation($average);
}
echo '</td>';

echo '</tr>';

// ----------- Epargne

echo '<tr class="tableRowTitle">';

echo '<td style="text-align: left;">'.$translator->getTranslation('Epargne').'</td>';
$index = 0;
$currentMonth = 0;
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

	echo '<td style="text-align: right;">';
	$total += ($monthTotalIncome[$month] - $monthTotalExpense[$month]);
	echo $translator->getCurrencyValuePresentation($monthTotalIncome[$month] - $monthTotalExpense[$month]);
	echo '</td>';
}
echo '<td style="text-align: right;">';
echo $translator->getCurrencyValuePresentation($total);
echo '</td>';
echo '<td style="text-align: right;">';
echo '</td>';
echo '</tr>';
?>
</tbody>
</table>
 
<h1><?= $translator->getTranslation('Mouvement des comptes privés') ?></h1>
<?php
$accountsManager = new AccountsManager();
$accounts = $accountsManager->GetAllPrivateAccounts();
?>	
<table id="recordsTable">
<thead>
<tr class="tableRowTitle">
<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Month') ?><br></td>
<?php
foreach ($accounts as $account)
{
?>
<td colspan='2'><?= $account->getName() ?></td>
<?php
}
?>
</tr>
<tr class="tableRowTitle">
<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Month') ?><br></td>
<?php
foreach ($accounts as $account)
{
?>
<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Crédit') ?><br></td>
<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Débit') ?><br></td>
<?php
}
?>
</tr>
</thead>
<tbody>
<?php
$index = 0;
$currentMonth = 0;
for ($month = 0; $month < 24; $month++)
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

	echo '<td style="text-align: right;">';
	echo $translator->getMonthYearPresentation($currentMonth, $currentYear);
	echo '</td>';
	
	foreach ($accounts as $account)
	{
		echo '<td style="text-align: right;">';
		$value = $account->GetTotalCreditByActorAndMonthAndYear(1, $currentMonth, $currentYear);
		if ($value > 0)
		{
			echo $translator->getCurrencyValuePresentation($value);
		}
		echo '</td>';

		echo '<td style="text-align: right;">';
		$value = $account->GetTotalDebitByMonthAndYear($currentMonth, $currentYear);
		if ($value > 0)
		{
			echo $translator->getCurrencyValuePresentation($value);
		}
		echo '</td>';
	}

	echo '</tr>';
}
?>
</tbody>
</table>