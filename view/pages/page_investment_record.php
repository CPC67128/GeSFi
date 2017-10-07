<?php

$investmentsRecordsHandler = new InvestmentsRecordsHandler();
$result = $investmentsRecordsHandler->GetAllRecords(12 * 10);

$now = date('Y-m-d');
$lastKnownValue = null;
$paymentAccumulated = 0;
$paymentInvestedAccumulated = 0;

$creationDate = $activeAccount->get('creationDate');

// ------ Display a title row
function AddTitleRow()
{
	global $translator, $activeInvestment, $accountType, $activeAccount;
	?>
	<tr class="titleRow">
	<td><?= $translator->getTranslation('Date') ?></td>
	<td><?= $translator->getTranslation('J') ?></td>
	<td><?= $translator->getTranslation('Désignation') ?></td>

	<?php if ($accountType != 12) { ?>
	<td><?= $translator->getTranslation('Versement') ?><br /><i><small>(&sum;) <?= $translator->getTranslation('Cumul') ?></small></i></td>
	<td><?= $translator->getTranslation('Versement effectif') ?><br /><i><small>(&sum;) <?= $translator->getTranslation('Cumul') ?></small></i></td>
	<td><?= $translator->getTranslation('Rachat') ?><br /><i><small>(&sum;) <?= $translator->getTranslation('Cumul') ?></small></i></td>
	<!-- <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Frais') ?></td> -->
	<?php } ?>
	<td><?= $translator->getTranslation('Valorisation') ?></td>
	<?php if ($accountType != 12) { ?>
	<td><?= $translator->getTranslation('Rendement') ?></td>
	<td><?= $translator->getTranslation('Rendement annuel') ?></td>
	<?php } ?>

	<?php if ($activeAccount->get('generateIncome') == 1) {?>
	<td><?= $translator->getTranslation('Revenu') ?></td>
	<?php } ?>

	<td></td>
	</tr>
	<?php
}

function PrintTRClass($row, $index)
{
	global $now;

	echo 'tableRow';

	if ($row['marked_as_deleted'])
		echo 'Deleted';
	else if ($row['record_date'] > $now)
		echo 'ToCome';
	else if ($row['record_type'] == 2)
		echo 'Remark';

	echo ($index % 2);
}

// ------ Add a data row
function AddRow($index, $row)
{
	global $activeInvestment, $now, $translator, $lastKnownValue;
	global $paymentAccumulated, $paymentInvestedAccumulated;
	global $creationDate, $accountType, $activeAccount;

	?>
	<tr class="<?php PrintTRClass($row, $index); ?>">
	<?php

	echo '<td>'.$row['record_date'].'</td>';
	echo '<td style="text-align: right;">'.$row['CALC_days_since_creation'].'</td>';
	echo '<td style="text-align: left;">'.$row['designation'].'</td>';
	if ($accountType != 12) {
	?>
	<td style="text-align: right;"><?php if ($row['amount'] != null) { ?><?= $translator->getCurrencyValuePresentation($row['amount']) ?><br /><i><small>(&sum;) <?= $translator->getCurrencyValuePresentation($row['CALC_amount_accumulated'])?></small></i><?php } ?></td>
	<td style="text-align: right;"><?php if ($row['amount_invested'] != null) { ?><?= $translator->getCurrencyValuePresentation($row['amount_invested']) ?><br /><i><small>(&sum;) <?= $translator->getCurrencyValuePresentation($row['CALC_amount_invested_accumulated'])?></small></i><?php } ?></td>
	<td style="text-align: right;"><?php if ($row['withdrawal'] != null) { ?><?= $translator->getCurrencyValuePresentation($row['withdrawal']) ?><br /><i><small>(&sum;) <?= $translator->getCurrencyValuePresentation($row['CALC_withdrawal_sum'])?></small></i><?php } ?></td>
	<?php
	}
	?>
	<td style="text-align: right;"><?= (isset($row['value'])) ? $translator->getCurrencyValuePresentation($row['value']) : '' ?></td>
	<?php
	if ($accountType != 11 && $accountType != 12) {
	echo '<td style="text-align: right;">'.$translator->getPercentagePresentation($row['CALC_yield']).'</td>';
	echo '<td style="text-align: right;">'.$translator->getPercentagePresentation($row['CALC_yield_average']).'</td>';
	}

	// Trash bin

	?>
	<?php if ($activeAccount->get('generateIncome') == 1) {?>
	<td><?php if (!empty($row['income'])) { ?><?= $translator->getCurrencyValuePresentation($row['income']) ?><br /><i><small>(&sum;) <?= $translator->getCurrencyValuePresentation($row['CALC_income_sum'])?></small></i><?php } ?></td>
	<?php } ?>
	<td style='text-align: center;'><span class='ui-icon ui-icon-trash' onclick='if (confirm("<?= $translator->getTranslation('Etes-vous sûr de vouloir supprimer cette entrée ?') ?>")) { DeleteRecordInvestment("<?= $row['record_id'] ?>"); }'></span></td>
	<?php 
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