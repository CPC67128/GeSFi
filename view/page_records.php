<?php
$accountPlannedDebit = $activeAccount->GetPlannedOutcome(10);

// ------------ Affichage du solde d'un compte réel
if ($accountType != 2)
{
	$accountExpectedMinimumBalance = $activeAccount->getExpectedMinimumBalance();
	$balance = $activeAccount->GetBalance();

	$criticalAccountBalance = false;
	if ($accountExpectedMinimumBalance >= ($balance + $accountPlannedDebit))
		$criticalAccountBalance = true;

	if ($criticalAccountBalance)
		echo "<font color='red'>";
	else
		echo "<font color='green'>";
?>
Solde : <?= $translator->getCurrencyValuePresentation($balance) ?>
</font> (Débit prévus pour les 10 prochains jours : <?= $translator->getCurrencyValuePresentation($accountPlannedDebit) ?>)
<?php
}
else
{
?>
Débit prévus pour les 10 prochains jours : <?= $translator->getCurrencyValuePresentation($accountPlannedDebit) ?>
<?php
}
?>
<br /><br />
<?php

$recordsManager = new RecordsManager();
$fullView = false;

if (isset($_GET['fullview']))
	$fullView = true;

if ($fullView)
	$result = $recordsManager->GetAllRecords(12 * 10);
else
	$result = $recordsManager->GetAllRecords(3);

$now = date('Y-m-d');

// ------ Display a title row
function AddTitleRow()
{
	global $translator, $activeAccount;
	?>
	<tr class="tableRowTitle">
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Date') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Désignation') ?></td>
	<?php if ($activeAccount->getType() != 1) { ?>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Effectuée par') ?></td>
	<?php } ?>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Montant') ?></td>
	<?php if ($activeAccount->getType() != 1) { ?>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Prise en charge') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;">/ <?= $activeAccount->GetOwnerName() ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;">/ <?= $activeAccount->GetCoownerName() ?></td>
	<?php } ?>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Catégorie') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"></td>
	</tr>
	<?php
}

// ------ Add a data row
function AddRow($index, $row, $mergeRow)
{
	global $activeAccount, $now, $translator;

	$tr = '<tr class="tableRow';
	if ($row['marked_as_deleted']) $tr .= 'Deleted';
	else if ($row['record_date'] > $now) $tr .= 'ToCome';
	else if ($row['record_type'] == 2) $tr .= 'Remark';
	echo $tr;

	if ($index % 2 == 0) echo '0">'; else echo '1">';

	if (!$mergeRow)
	{
		echo '<td>'.($row['record_date'] == 0 ? '' : $row['record_date']).'</td>';
		echo '<td style="text-align: left;">'.$row['designation'].'</td>';
		if ($activeAccount->getType() != 1)
			echo '<td style="text-align: right;">'.($row['actor'] == 1 ? $activeAccount->GetOwnerName() : $activeAccount->GetCoownerName() ).'</td>';
	}
	else
	{
		echo '<td></td>';
		echo '<td></td>';
		if ($activeAccount->getType() != 1) echo '<td></td>';
	}
	echo '<td style="text-align: right;">';
	if ($row['record_type'] == 0 || $row['record_type'] == 3)
		echo '<font color="blue">';
	else if ($row['record_type'] == 10)
		echo '<font color="DarkBlue">';
	else if ($row['record_type'] == 20)
		echo '<font color="DarkRed">';
	else
		echo '<font color="red">';
	
	if ($row['record_type'] < 2 || $row['record_type'] >= 3)
		echo $translator->getCurrencyValuePresentation($row['amount']);

	echo '</font>';
	echo '</td>';

	if ($activeAccount->getType() != 1)
	{
		echo '<td style="text-align: right;">';
		if ($row['record_type'] == 1 || $row['record_type'] == 4)
			echo $row['charge'].'&nbsp;%';
		echo '</td>';
		echo '<td style="text-align: right;">';
		if ($row['record_type'] == 1 || $row['record_type'] == 4)
			echo $translator->getCurrencyValuePresentation($row['part_actor1']);
		echo '</td>';
		echo '<td style="text-align: right;">';
		if ($row['record_type'] == 1 || $row['record_type'] == 4)
			echo $translator->getCurrencyValuePresentation($row['part_actor2']);
		echo '</td>';
	}
	echo '<td style="text-align: right;">';
	if ($row['record_type'] == 1 || $row['record_type'] == 4)
	{
		if ($row['link_type'] == 'USER')
		{
			echo $translator->getTranslation('Privée');
			echo ' / ';
		}
		echo $row['category'];
	}
	echo '</td>';
	echo '<td style="text-align: center;"><span class="ui-icon ui-icon-trash" onclick="if (confirm(\''.$translator->getTranslation('Etes-vous sûr de vouloir supprimer cette entrée ?').'\')) { DeleteRecord(\''.$row['record_id'].'\'); }"></span></td>';
	echo '</tr>';
}

// ------ Add a subtotal line in case of merged row
function AddSubTotalRow($index, $row, $subtotal)
{
	global $activeAccount, $now, $translator;

	$tr = '<tr class="tableRow';
	if ($row['marked_as_deleted']) $tr .= 'Deleted';
	else if ($row['record_date'] > $now) $tr .= 'ToCome';
	else if ($row['record_type'] == 2) $tr .= 'Remark';
	echo $tr;

	if ($index % 2 == 0) echo '0">'; else echo '1">';

	echo '<td></td><td></td>';
	if ($activeAccount->getType() != 1) { echo '<td></td>'; }

	echo '<td style="text-align: right;">';
	if ($row['record_type'] == 0 || $row['record_type'] == 3) echo '<font color="blue">';
	else if ($row['record_type'] == 10) echo '<font color="DarkBlue">';
	else if ($row['record_type'] == 20) echo '<font color="DarkRed">';
	else echo '<font color="red">';

	echo '<i>= '.$translator->getCurrencyValuePresentation($subtotal).'</i>';
	echo '</font>';
	echo '</td>';

	if ($activeAccount->getType() != 1) echo '<td></td><td></td><td></td><td></td>';

	echo '<td style="text-align: center;"><span class="ui-icon ui-icon-trash" onclick="if (confirm(\''.$translator->getTranslation('Etes-vous sûr de vouloir supprimer cette entrée ?').'\')) { DeleteRecord(\''.$row['record_id'].'\'); }"></span></td>';
	echo '</tr>';
}

$index = 0;
$previousRow = null;
$subTotal = 0;
$mergeRow = false;
?>
<table id="recordsTable">
<thead>
<?php AddTitleRow(); ?>
</thead>
<tbody>
<?php
while ($row = $result->fetch())
{
	// Display only deleted rows in full view
	if ($row['marked_as_deleted'] && !$fullView)
		continue;

	if ($row['record_date'] <= $now)
	{
		if ($previousRow != null && $previousRow['record_date_month'] != $row['record_date_month'])
		{
			AddTitleRow();
		}
	}

	// ------ Merging of rows if similar group
	if ($row['record_group_id'] == '' || $previousRow == null || $previousRow['record_group_id'] != $row['record_group_id'])
	{
		if ($mergeRow)
		{
			AddSubTotalRow($index, $previousRow, $subTotal);
		}
		$index++;
		$mergeRow = false;
		$subTotal = 0;
	}
	else
	{
		$mergeRow = true;
	}

	AddRow($index, $row, $mergeRow);
	$subTotal += $row['amount'];

	$previousRow = $row;
}
?>
  </tbody>
</table>

<br />
<button onclick="LoadAllRecords();">Voir toutes les lignes</button>