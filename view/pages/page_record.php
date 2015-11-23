<div id="accountStatus"></div>

<?php
$recordsHandler = new RecordsHandler();

if ($fullRecordsView)
	$result = $recordsHandler->GetAllRecords(12 * 5);
else
	$result = $recordsHandler->GetAllRecords(3);

$now = date('Y-m-d');

// ------ Display a title row
function AddTitleRow()
{
	global $translator;
	?>
	<tr class="titleRow">
	<td><?= $translator->getTranslation('Date') ?></td>
	<td><?= $translator->getTranslation('Désignation') ?></td>
	<td><?= $translator->getTranslation('Effectuée par') ?></td>
	<td><?= $translator->getTranslation('Compte') ?></td>
	<td><?= $translator->getTranslation('Cnf') ?></td>
	<td><?= $translator->getTranslation('Montant') ?></td>
	<td><?= $translator->getTranslation('Catégorie') ?></td>
	<td><?= $translator->getTranslation('Prise en charge') ?></td>
	<td></td>
	</tr>
	<?php
}

// ------------------------------------------------------------------
// ------ Row
// ------------------------------------------------------------------

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

function PrintDate($mergeRow, $row)
{
	if (!$mergeRow && $row['record_date'] != 0)
		echo $row['record_date'];
}

function PrintModifyRecordDesignationFunction($mergeRow, $row)
{
	?>ondblclick="ModifyRecordDesignation('<?= $row['record_id'] ?>', '<?= $row['designation'] ?>', this);"<?php
}

function PrintConfirmedBox($mergeRow, $row, $activeAccount)
{
	if (!$mergeRow)
	{
		if (!empty($row['account_id']) && (isset($activeAccount) && $activeAccount->get('recordConfirmation') == 1))
		{
			?><input type="checkbox" <?= $row['confirmed'] == 1 ? 'checked' : '' ?> onclick="ConfirmRecord('<?= $row['record_id'] ?>', this);"></td><?php 
		}
	}
}

function PrintAmountClass($row, $index)
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

function PrintModifyRecordAmountFunction($row)
{
	?>ondblclick="ModifyRecordAmount('<?= $row['record_id'] ?>', '<?= $row['amount'] ?>', this);"<?php
}

function PrintAmount($row)
{
	global $translator;
	if ($row['record_type'] != 2)
	{
		echo $translator->getCurrencyValuePresentation($row['amount']);
	}
}

function PrintCategory($row)
{
	global $usersHandler;

	if (isset($row['category']))
	{
		echo $row['category'];
	}
	else if (isset($row['category_id']) && substr($row['category_id'], 0, 5) == "USER/")
	{
		$user = $usersHandler->GetUser(substr($row['category_id'], 5, 36));
	
		echo 'Non définie / '.$user->getName();
	}
}

function PrintModifyRecordChargeFunction($row)
{
	if (isset($row['category']) || (isset($row['category_id']) && substr($row['category_id'], 0, 5) == "USER/"))
	{
		?> ondblclick="ModifyRecordCharge('<?= $row['record_id'] ?>', '<?= $row['charge'] ?>', this);"<?php
	}
}

function PrintCharge($row)
{
	global $translator, $activeUser, $partnerUser;
	if (isset($row['category']) || (isset($row['category_id']) && substr($row['category_id'], 0, 5) == "USER/"))
	{
		?><img src='../media/information.png' title='<?= $activeUser->get('name')."=".$translator->getCurrencyValuePresentation($row['part_actor1'])." / ".$partnerUser->get('name')."=".$translator->getCurrencyValuePresentation($row['part_actor2']) ?>'> <?= $row['charge']?> %<?php
	}
}

function AddRow($index, $row, $mergeRow)
{
	global $accountsHandler, $recordsHandler, $now, $translator, $activeUser, $partnerUser;

	$activeAccount = '';
	try {
		if (!empty($row['account_id']))
			$activeAccount = $accountsHandler->GetAccount($row['account_id']);
	} catch (Exception $e) {

	}
?>

<tr class="<?php PrintTRClass($row, $index); ?>">
<td><?php PrintDate($mergeRow, $row) ?></td>
<td <?php PrintModifyRecordDesignationFunction($mergeRow, $row) ?>><?= !$mergeRow ? $row['designation'] : '' ?></td>
<td><?= !$mergeRow ? $row['user_name'] : '' ?></td>
<td><?= !$mergeRow ? $row['account_name'] : '' ?></td>
<td><?php PrintConfirmedBox($mergeRow, $row, $activeAccount) ?></td>
<td class="amount<?= $recordsHandler->GetRecordTypeGroup($row['record_type']) ?>" <?php PrintModifyRecordAmountFunction($row) ?>><?php PrintAmount($row) ?></td>
<td class="category<?= $row['link_type'] ?>"><?php PrintCategory($row); ?></td>
<td <?php PrintModifyRecordChargeFunction($row); ?>><?php PrintCharge($row); ?></td>
<td style='text-align: center;'><span class='ui-icon ui-icon-trash' onclick='if (confirm("<?= $translator->getTranslation('Etes-vous sûr de vouloir supprimer cette entrée ?')?>")) { DeleteRecord("<?= $row['record_id']?>"); }'></span></td>
</tr>
	<?php
}

// ------ Subtotal line in case of merged rows

function PrintAmountSumClass($row)
{
	if ($row['record_type'] == 0 || $row['record_type'] == 3)
		echo 'amountSumIn';
	else
		echo 'amountSumOut';
}

function AddSubTotalRow($index, $row, $subtotal)
{
	global $activeAccount, $now, $translator;

?><tr class="<?php PrintTRClass($row, $index); ?>">
<td></td><td></td><td></td><td></td><td></td>
<td class="<?= PrintAmountSumClass($row) ?>">= <?= $translator->getCurrencyValuePresentation($subtotal) ?></td>
<td></td><td></td>
<td style="text-align: center;"><span class="ui-icon ui-icon-trash" onclick="if (confirm(\''.$translator->getTranslation('Etes-vous sûr de vouloir supprimer cette entrée ?').'\')) { DeleteRecord(\''.$row['record_id'].'\'); }"></span></td>
</tr>
<?php
}

// ------------------------------------------------------------------

$index = 0;
$previousRow = null;
$subTotal = 0;
$mergeRow = false;
?>
<table id="recordsTable">
<?php if ($activeAccount->get('type') > 0) { ?>
<thead>
<tr class="headerRowLoan" style="background-color: <?= $activeAccount->GetAccountTypeColor() ?>;">
<td colspan="9"><?= $activeAccount->GetAccountTypeName() ?> : <?= $activeAccount->get('name') ?></td>
</tr>
</thead>
<?php } ?>
<tbody>
<?php AddTitleRow(); ?>
<?php
while ($row = $result->fetch())
{
	// Display only deleted rows in full view
	if ($row['marked_as_deleted'] && !$fullRecordsView)
		continue;

	// ------ Merging of rows if similar group
	if ($row['record_group_id'] == '' || $previousRow == null || $previousRow['record_group_id'] != $row['record_group_id'] || $previousRow['record_type'] != $row['record_type'])
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

	if ($row['record_date'] <= $now)
	{
		if ($previousRow != null && $previousRow['record_date_month'] != $row['record_date_month'])
		{
			AddTitleRow();
		}
	}
	AddRow($index, $row, $mergeRow);
	$subTotal += $row['amount'];

	$previousRow = $row;
}
?>
  </tbody>
</table>

<br />
<?php if (!$fullRecordsView) { ?>
<button onclick="LoadRecords_All();">Voir toutes les lignes</button>
<?php } else { ?>
<button onclick="LoadRecords_Normal();">Revenir à la vue normale</button>
<?php } ?>

<script type="text/javascript">

$(function() {
	var newValue = $("#newValue");
	var type = $("#type");
	var recordId = $("#recordId");

	$( "#dialog-form-modifyValue" ).dialog({
	    autoOpen: false,
	    height: 160,
	    width: 400,
	    modal: true,
	    buttons: {
	      "Modifier": function() {
	    		$.post (
	    				'../controller/controller.php?action=record_modify_' + type.val(),
	    				{ recordId: recordId.val() , newValue: newValue.val() },
	    				function(response, status) {
	    					$( "#dialog-form-modifyValue" ).dialog( "close" );
	    					LoadPage();
						}
	    			);
				
	      },
	      Cancel: function() {
	        $( this ).dialog( "close" );
	      }
	    },
	    close: function() {
	    }
	  });

	$("#dialog-form-modifyValue").keydown(function (event) {
        if (event.keyCode == 13) {
            $(this).parent()
                   .find("button:eq(1)").trigger("click");
            return false;
        }
    });

	$( "#newValue" ).focus(function() { $(this).select(); } );
});

function ModifyRecordAmount(recordIdToModify, amount, sender)
{
	$("#newValue").val(amount);
	$("#type").val("Amount");
	ModifyRecordOpen(recordIdToModify);
}

function ModifyRecordDesignation(recordIdToModify, designation, sender)
{
	$("#newValue").val(designation);
	$("#type").val("Designation");
	ModifyRecordOpen(recordIdToModify);
}

function ModifyRecordCharge(recordIdToModify, charge, sender)
{
	$("#newValue").val(charge);
	$("#type").val("Charge");
	ModifyRecordOpen(recordIdToModify);
}

function ModifyRecordOpen(recordIdToModify)
{
	$("#recordId").val(recordIdToModify);
	$("#dialog-form-modifyValue").dialog("open");
}

LoadAccountStatusInPageRecord();

</script>

<div id="dialog-form-modifyValue" title="<?= $translator->getTranslation('Modifier') ?>">
  <form>
  <fieldset>
    <label for="charge">Nouvelle valeur</label>
    <input type="text" name="newValue" autocomplete="off" id="newValue" class="text ui-widget-content ui-corner-all">
    <input type="hidden" name="recordId" id="recordId">
    <input type="hidden" name="type" id="type">
  </fieldset>
  </form>
</div>