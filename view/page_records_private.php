<?php
$balance = $activeAccount->GetBalance();
$expectedMinimumBalance = $activeAccount->getExpectedMinimumBalance();
$plannedOutcome = $activeAccount->GetPlannedOutcome(10);

$criticalAccountBalance = false;
if ($expectedMinimumBalance >= ($balance + $plannedOutcome))
	$criticalAccountBalance = true;

if ($criticalAccountBalance)
	echo "<font color='red'>";
else
	echo "<font color='green'>";
?>
Solde : <?= $translator->getCurrencyValuePresentation($balance) ?>
</font> (Débits prévus pour les 10 prochains jours : <?= $translator->getCurrencyValuePresentation($plannedOutcome) ?>)
<br /><br />
<table id="recordsTable">
    <thead>
      <tr class="tableRowTitle">
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Date') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Désignation') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Montant') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Catégorie') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><br>
        </td>
      </tr>
    </thead>
    <tbody>
<?php

$recordsManager = new RecordsManager();
$fullView = false;

if (isset($_GET['fullview']))
	$fullView = true;

if ($fullView)
	$result = $recordsManager->GetAllRecords(12 * 10);
else
	$result = $recordsManager->GetAllRecords(3);

$index = 0;
$now = date('Y-m-d');
$last_month_displayed = -1;
$oldUUID = '';

while($data = $result->fetch())
{
	if ($data['marked_as_deleted'] && !$fullView)
		continue;

	if ($last_month_displayed == -1)
	{
		$last_month_displayed = $data['record_date_month'];
	}

	if ($data['record_date'] <= $now)
	{
		if ($data['record_date_month'] != $last_month_displayed)
		{
			$last_month_displayed = $data['record_date_month'];
?>
      <tr class="tableRowTitle">
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Date') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Désignation') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Montant') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Catégorie') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><br>
        </td>
      </tr>
<?php
		}
	}

	if ($data['marked_as_deleted'])
		echo '<tr class="tableRowDeleted';
	else if ($data['record_date'] > $now)
		echo '<tr class="tableRowToCome';
	else if ($data['record_type'] == 2)
		echo '<tr class="tableRowRemark';
	else
		echo '<tr class="tableRow';

	$mergeRow = false;
	if ($data['record_group_id'] == '')
		$index++;
	elseif ($oldUUID != $data['record_group_id'])
		$index++;
	else
		$mergeRow = true;

	if ($index % 2 == 0)
		echo '0">';
	else
		echo '1">';

	if (!$mergeRow)
	{
		echo '<td>'.($data['record_date'] == 0 ? '' : $data['record_date']).'</td>';
		echo '<td style="text-align: left;">'.$data['designation'].'</td>';
	}
	else
	{
		echo '<td></td>';
		echo '<td></td>';
	}
	echo '<td style="text-align: right;">';
	if ($data['record_type'] == 3)
		echo '<font color="blue">';
	else
		echo '<font color="red">';
	if ($data['record_type'] == 3 || $data['record_type'] == 4)
		echo $translator->getCurrencyValuePresentation($data['amount']);
	if ($data['record_type'] == 3)
		echo '</font>';
	else
		echo '</font>';
	echo '</td>';
	echo '<td style="text-align: right;">'.$data['category'].'</td>';
	echo '<td style="text-align: center;"><span class="ui-icon ui-icon-trash" onclick="if (confirm(\''.$translator->getTranslation('Etes-vous sûr de vouloir supprimer cette entrée ?').'\')) { DeleteRecord(\''.$data['record_id'].'\'); }"></span></td>';
	$oldUUID = $data['record_group_id'];
}

?>
  </tbody>
</table>

<br />
<button onclick="LoadAllRecords();">Voir toutes les lignes</button>
