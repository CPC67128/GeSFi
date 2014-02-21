<?php

$investmentsRecordsManager = new InvestmentsRecordsManager();

$investmentsRecordsManager->CalculateIndicators();
$result = $investmentsRecordsManager->GetAllRecords(12 * 10);

$now = date('Y-m-d');
$lastKnownValue = null;
$paymentAccumulated = 0;
$paymentInvestedAccumulated = 0;

$creationDate = $activeAccount->get('creationDate');

// ------ Display a title row
function AddTitleRow()
{
	global $translator, $activeInvestment, $accountType;
	?>
	<tr class="tableRowTitle">
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Date') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('J') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Désignation') ?></td>

	<?php if ($accountType != 11 && $accountType != 12) { ?>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Versement') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><small><?= $translator->getTranslation('Cumul') ?></small></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Versement effectif') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><small><?= $translator->getTranslation('Cumul') ?></small></td>
	<!-- <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Frais') ?></td> -->
	<?php } ?>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Valorisation') ?></td>
	<?php if ($accountType != 11 && $accountType != 12) { ?>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Rendement') ?></td>
	<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Rendement annuel') ?></td>
	<?php } ?>
	<td style="vertical-align: top; text-align: center; font-style: italic;"></td>
	</tr>
	<?php
}

// ------ Add a data row
function AddRow($index, $row)
{
	global $activeInvestment, $now, $translator, $lastKnownValue;
	global $paymentAccumulated, $paymentInvestedAccumulated;
	global $creationDate, $accountType;

	$tr = '<tr class="tableRow';
	if ($row['record_type'] == 2) $tr .= 'Remark';
	//if ($row['marked_as_deleted']) $tr .= 'Deleted';
	echo $tr;

	if ($index % 2 == 0) echo '0">'; else echo '1">';

	echo '<td>'.$row['record_date'].'</td>';
	echo '<td style="text-align: right;">'.$row['CALC_days_since_creation'].'</td>';
	echo '<td style="text-align: left;">'.$row['designation'].'</td>';
	if ($accountType != 11 && $accountType != 12) {
	echo '<td style="text-align: right;">'.($row['payment'] == null ? '' : $translator->getCurrencyValuePresentation($row['payment'])).'</td>';
	echo '<td style="text-align: right; font-style: italic;"><small>'.$translator->getCurrencyValuePresentation($row['CALC_payment_accumulated']).'</small></td>';
	echo '<td style="text-align: right;">'.($row['payment_invested'] == null ? '' : $translator->getCurrencyValuePresentation($row['payment_invested'])).'</td>';
	echo '<td style="text-align: right; font-style: italic;"><small>'.$translator->getCurrencyValuePresentation($row['CALC_payment_invested_accumulated']).'</small></td>';
	}
	echo '<td style="text-align: right;">'.(isset($row['value']) ? $translator->getCurrencyValuePresentation($row['value']) : '').'</td>';
	if ($accountType != 11 && $accountType != 12) {
	echo '<td style="text-align: right;">'.$translator->getPercentagePresentation($row['CALC_yield']).'</td>';
	echo '<td style="text-align: right;">'.$translator->getPercentagePresentation($row['CALC_yield_average']).'</td>';
	}

	// Trash bin
	echo "<td style='text-align: center;'><span class='ui-icon ui-icon-trash' onclick='if (confirm(\"".$translator->getTranslation('Etes-vous sûr de vouloir supprimer cette entrée ?')."\")) { DeleteRecordInvestment(\"".$row['investment_record_id']."\"); }'></span></td>";

	echo '</tr>';
}

$index = 0;
$previousRow = null;
$subTotal = 0;
$mergeRow = false;
?>

<table id="recordsTable">
<tbody>
<?php
while ($row = $result->fetch())
{
	// ------ Merging of rows if similar group
	if ($previousRow == null)
	{
		AddTitleRow();
	}

	AddRow($index, $row, $mergeRow);

	$previousRow = $row;
	$index++;
}

if ($previousRow == null)
	AddTitleRow();
?>
  </tbody>
</table>