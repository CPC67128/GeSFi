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
	global $translator, $activeAccount;
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
function AddRow($index, $row, $mergeRow)
{
	global $accountsHandler, $recordsHandler, $now, $translator, $activeUser, $partnerUser;

	try {
		if (!empty($row['account_id']))
			$activeAccount = $accountsHandler->GetAccount($row['account_id']);
	} catch (Exception $e) {

	}

	$tr = '<tr class="tableRow';
	if ($row['marked_as_deleted']) $tr .= 'Deleted';
	else if ($row['record_date'] > $now) $tr .= 'ToCome';
	else if ($row['record_type'] == 2) $tr .= 'Remark';
	echo $tr;

	if ($index % 2 == 0) echo '0">'; else echo '1">';

?>

<td><?= !$mergeRow && $row['record_date'] != 0 ? $row['record_date'] : '' ?></td>

<td style="text-align: left;" ondblclick="ModifyRecordDesignation('<?= $row['record_id'] ?>', '<?= $row['designation'] ?>', this);">
<?= !$mergeRow ? $row['designation'] : '' ?>
</td>

<td style="text-align: left;"><?= !$mergeRow ? $row['user_name'] : '' ?></td>

<td style="text-align: left;"><?= !$mergeRow ? $row['account_name'] : '' ?></td>

<td>
<?php if (!$mergeRow && !(empty($row['account_id']) || $activeAccount->get('recordConfirmation') != 1)) { ?>
<input type="checkbox" <?= $row['confirmed'] == 1 ? 'checked' : '' ?> onclick="ConfirmRecord('<?= $row['record_id'] ?>', this);"></td>
<?php } ?>
</td>

<td style="text-align: right;" ondblclick="ModifyRecord('<?= $row['record_id'] ?>', '<?= $row['amount'] ?>', this);">
<font color="<?= $recordsHandler->GetRecordTypeCoulour($row['record_type']) ?>">
<?= $row['record_type'] != 2 ? $translator->getCurrencyValuePresentation($row['amount']) : '' ?>	
</font>
</td>

<?php
	// Category
	if (isset($row['category']))
	{
		echo "<td style='text-align: left;'>";
	
		if ($row['link_type'] == 'USER') echo "<font color='DarkGreen'>";
		else if ($row['link_type'] == 'DUO') echo "<font color='MediumVioletRed'>";
	
		echo $row['category'];
	
		echo "</font>";
		echo "</td>";
	}
	else if (isset($row['category_id']) && substr($row['category_id'], 0, 5) == "USER/")
	{
		echo "<td style='text-align: left;'>";
	
		$usersHandler = new UsersHandler();
		$user = $usersHandler->GetUser(substr($row['category_id'], 5, 36));

		echo "<font color='DarkGreen'>";
	
		echo 'Non définie / '.$user->getName();
	
		echo "</font>";
		echo "</td>";
	}
	else
		echo "<td></td>";

	// Charge level
	echo '<td style="text-align: left;">';

	if (isset($row['category']) || (isset($row['category_id']) && substr($row['category_id'], 0, 5) == "USER/"))
	{
		echo "<img src='../media/information.png' title='";
		echo $activeUser->get('name')."=".$translator->getCurrencyValuePresentation($row['part_actor1'])." / ".$partnerUser->get('name')."=".$translator->getCurrencyValuePresentation($row['part_actor2']);
		echo "'>";
		echo "&nbsp;";
	}

	if ($row['link_type'] == 'DUO' || $row['link_type'] == 'USER' || (isset($row['category_id']) && substr($row['category_id'], 0, 5) == "USER/"))
		echo $row['charge'].'&nbsp;%';
	echo '</td>';

	// Trash bin
	echo "<td style='text-align: center;'><span class='ui-icon ui-icon-trash' onclick='if (confirm(\"".$translator->getTranslation('Etes-vous sûr de vouloir supprimer cette entrée ?')."\")) { DeleteRecord(\"".$row['record_id']."\"); }'></span></td>";

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
	echo '<td></td>';
	echo '<td></td>';	
	echo "<td></td>";

	echo '<td style="text-align: right;">';
	if ($row['record_type'] == 0 || $row['record_type'] == 3) echo '<font color="blue">';
	else if ($row['record_type'] == 10) echo '<font color="DarkBlue">';
	else if ($row['record_type'] == 20) echo '<font color="DarkRed">';
	else echo '<font color="red">';

	echo '<i>= '.$translator->getCurrencyValuePresentation($subtotal).'</i>';
	echo '</font>';
	echo '</td>';

	echo '<td></td><td></td>';

	echo '<td style="text-align: center;"><span class="ui-icon ui-icon-trash" onclick="if (confirm(\''.$translator->getTranslation('Etes-vous sûr de vouloir supprimer cette entrée ?').'\')) { DeleteRecord(\''.$row['record_id'].'\'); }"></span></td>';
	echo '</tr>';
}

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
	var amount = $( "#amount" );
	var designation = $( "#designation" );
	var recordId = $( "#recordId" );

	$( "#dialog-form" ).dialog({
	    autoOpen: false,
	    height: 160,
	    width: 350,
	    modal: true,
	    buttons: {
	      "Modifier": function() {
	    		$.post (
	    				'../controller/controller.php?action=record_modify_amount',
	    				{ recordId: recordId.val() , amount: amount.val() },
	    				function(response, status) {
	    					$( "#dialog-form" ).dialog( "close" );
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

	$("#dialog-form").keydown(function (event) {
        if (event.keyCode == 13) {
            $(this).parent()
                   .find("button:eq(1)").trigger("click");
            return false;
        }
    });


	$( "#dialog-form-designation" ).dialog({
	    autoOpen: false,
	    height: 160,
	    width: 400,
	    modal: true,
	    buttons: {
	      "Modifier": function() {
	    		$.post (
	    				'../controller/controller.php?action=record_modify_designation',
	    				{ recordId: recordId.val() , designation: designation.val() },
	    				function(response, status) {
	    					$( "#dialog-form-designation" ).dialog( "close" );
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

	$("#dialog-form-designation").keydown(function (event) {
        if (event.keyCode == 13) {
            $(this).parent()
                   .find("button:eq(1)").trigger("click");
            return false;
        }
    });

	$( "#amount" ).focus(function() { $(this).select(); } );
	$( "#designation" ).focus(function() { $(this).select(); } );
});

function ModifyRecord(recordIdToModify, amount, sender)
{
	$( "#recordId" ).val(recordIdToModify);
	$( "#amount" ).val(amount);
	$( "#dialog-form" ).dialog( "open" );
}

function ModifyRecordDesignation(recordIdToModify, designation, sender)
{
	$( "#recordId" ).val(recordIdToModify);
	$( "#designation" ).val(designation);
	$( "#dialog-form-designation" ).dialog( "open" );
}

LoadAccountStatusInPageRecord();

</script>

<div id="dialog-form" title="Modifier un montant">
  <form>
  <fieldset>
    <label for="amount">Nouveau montant</label>
    <input type="text" name="amount" autocomplete="off" id="amount" class="text ui-widget-content ui-corner-all">
    <input type="hidden" name="recordId" id="recordId">
    </fieldset>
  </form>
</div>

<div id="dialog-form-designation" title="Modifier une désignation">
  <form>
  <fieldset>
    <label for="designation">Nouvelle désignation</label>
    <input type="text" name="designation" autocomplete="off" id="designation" class="text ui-widget-content ui-corner-all">
    <input type="hidden" name="recordId" id="recordId">
    </fieldset>
  </form>
</div>