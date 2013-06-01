<?php
$statistics = new Statistics();

// ----- Joint account
$totalExpenseJointAccount = $statistics->GetTotalExpenseJointAccount();
$totalIncomeJointAccountByActor1 = $statistics->GetTotalIncomeJointAccountByActor(1);
$totalIncomeJointAccountByActor2 = $statistics->GetTotalIncomeJointAccountByActor(2);

$totalIncomeJointAccount = $totalIncomeJointAccountByActor1 + $totalIncomeJointAccountByActor2;
$balanceJointAccount = $totalIncomeJointAccount - $totalExpenseJointAccount;
$jointAccountExpectedMinimumBalance = $activeAccount->getExpectedMinimumBalance();
$jointAccountPlannedDebit = $statistics->GetJointAccountPlannedDebit(10);

$criticalJointAccountBalance = false;
if ($jointAccountExpectedMinimumBalance >= ($balanceJointAccount + $jointAccountPlannedDebit))
	$criticalJointAccountBalance = true;

if ($criticalJointAccountBalance)
	echo "<font color='red'>";
else
	echo "<font color='green'>";
?>
Solde compte commun : <?= $translator->getCurrencyValuePresentation($balanceJointAccount) ?>
</font> (Dépenses prévues pour les 10 prochains jours : <?= $translator->getCurrencyValuePresentation($jointAccountPlannedDebit) ?>)
<br />
<?php
// ----- Engagment comparison
$totalPrivateExpenseByActor1 = $statistics->GetTotalPrivateExpenseByActor(1);
$totalPrivateExpenseByActor2 = $statistics->GetTotalPrivateExpenseByActor(2);

$totalExpenseChargedPartByActor1 = $statistics->GetTotalExpenseChargedPartByActor(1);
$totalExpenseChargedPartByActor2 = $statistics->GetTotalExpenseChargedPartByActor(2);

$totalIncomeJointAccountByActor1 = $statistics->GetTotalIncomeJointAccountByActor(1);
$totalIncomeJointAccountByActor2 = $statistics->GetTotalIncomeJointAccountByActor(2);
$totalRepaymentByActor1 = $statistics->GetTotalRepaymentByActor(1);
$totalRepaymentByActor2 = $statistics->GetTotalRepaymentByActor(2);
$totalAmountGivenByActor1 = $totalIncomeJointAccountByActor1 + $totalPrivateExpenseByActor1 + $totalRepaymentByActor1 - $totalRepaymentByActor2; 
$totalAmountGivenByActor2 = $totalIncomeJointAccountByActor2 + $totalPrivateExpenseByActor2 + $totalRepaymentByActor2 - $totalRepaymentByActor1;

$differenceIncomeChargeActor1 = $totalAmountGivenByActor1 - $totalExpenseChargedPartByActor1;
$differenceIncomeChargeActor2 = $totalAmountGivenByActor2 - $totalExpenseChargedPartByActor2;
$difference = $differenceIncomeChargeActor1 - $differenceIncomeChargeActor2;

if ($difference > 0)
	echo $activeAccount->GetOwnerName().$translator->getTranslation(' a engagé en plus par rapport à ').$activeAccount->GetCoownerName();
else
	echo $activeAccount->GetCoownerName().$translator->getTranslation(' a engagé en plus par rapport à ').$activeAccount->GetOwnerName();
?>&nbsp;<?= $translator->getCurrencyValuePresentation(abs($difference)) ?>
<br /><br />
<table id="recordsTable">
    <thead>
      <tr class="tableRowTitle">
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Date') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Désignation') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Effectuée par') ?><br>
        </td>
		<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Compte commun') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Montant') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Prise en charge') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;">/ <?= $activeAccount->GetOwnerName() ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;">/ <?= $activeAccount->GetCoownerName() ?><br>
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
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Effectuée par') ?><br>
        </td>
		<td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Compte commun') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Montant') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;"><?= $translator->getTranslation('Prise en charge') ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;">/ <?= $activeAccount->GetOwnerName() ?><br>
        </td>
        <td style="vertical-align: top; text-align: center; font-style: italic;">/ <?= $activeAccount->GetCoownerName() ?><br>
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
		echo '<td style="text-align: right;">'.($data['actor'] == 1 ? $activeAccount->GetOwnerName() : $activeAccount->GetCoownerName() ).'</td>';
		echo '<td style="text-align: center;">';
		if ($data['record_type'] == 4 || $data['record_type'] == 3)
			echo 'X';
		echo '</td>';
	}
	else
	{
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
	}
	echo '<td style="text-align: right;">';
	if ($data['record_type'] == 0 || $data['record_type'] == 3)
		echo '<font color="blue">';
	else
		echo '<font color="red">';

	if ($data['record_type'] < 2)
		echo $translator->getCurrencyValuePresentation($data['amount']);
	if ($data['record_type'] >= 3)
		echo $translator->getCurrencyValuePresentation($data['amount']);

	if ($data['record_type'] == 0)
		echo '</font>';
	else
		echo '</font>';

	echo '</td>';
	echo '<td style="text-align: right;">';
	if ($data['record_type'] == 1 || $data['record_type'] == 4)
		echo $data['charge'].'&nbsp;%';
	echo '</td>';
	echo '<td style="text-align: right;">';
	if ($data['record_type'] == 1 || $data['record_type'] == 4)
		echo $translator->getCurrencyValuePresentation($data['part_actor1']);
	echo '</td>';
	echo '<td style="text-align: right;">';
	if ($data['record_type'] == 1 || $data['record_type'] == 4)
		echo $translator->getCurrencyValuePresentation($data['part_actor2']);
	echo '</td>';
	echo '<td style="text-align: right;">';
	if ($data['record_type'] == 1 || $data['record_type'] == 4)
	{
		if ($data['link_type'] == 'USER')
		{
			echo $translator->getTranslation('Privée');
			echo ' / ';
		}
		echo $data['category'];
	}
	echo '</td>';
	echo '<td style="text-align: center;"><span class="ui-icon ui-icon-trash" onclick="if (confirm(\''.$translator->getTranslation('Etes-vous sûr de vouloir supprimer cette entrée ?').'\')) { DeleteRecord(\''.$data['record_id'].'\'); }"></span></td>';
	$oldUUID = $data['record_group_id'];
}

?>
  </tbody>
</table>

<br />
<button onclick="LoadAllRecords();">Voir toutes les lignes</button>
