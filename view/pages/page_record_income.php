<h1><?= $translator->getTranslation('Saisir un versement') ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>
  <td style="vertical-align: top;">

<?= $translator->getTranslation('Vers le compte :') ?><br/>

<br/>

<?php
$accounts = $accountsHandler->GetAllDuoAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="toAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br />
<?php } ?>

<br/>

<input type="radio" name="toAccount" value="USER/<?= $activeUser->get('userId') ?>"><i><?= $activeUser->get('name') ?> / Compte inconnu</i><br />
<?php
$accounts = $accountsHandler->GetAllPrivateAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="toAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $activeUser->get('name') ?> / <?= $account->get('name') ?><br />
<?php } ?>


<br/>
<?= $translator->getTranslation('Date') ?> <input type="hidden" id="datePickerHidden" name="date" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline"></div><br/>
<?= $translator->getTranslation('Montant') ?> <input type="text" name="amount" style='background-color : #d1d1d1;' tabindex="-1" size="6" readonly>&nbsp;&euro;<br/>
<?= $translator->getTranslation('Désignation') ?> <input type="text" name="designation" id="designation" size="30" >
</td>

<td style="vertical-align: top;">
<table class="categoriesTable">
<thead>
<td><?= $translator->getTranslation('Catégorie') ?></td>
<td><?= $translator->getTranslation('Formule') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
<td><?= $translator->getTranslation('Prise en charge') ?></td>
</thead>
<thead>
<td>&nbsp;</td>
<td><font size=1>x-- = x - others<br />x+y-z</font></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</thead>
<tr>
<td colspan=4><b><i><?= $translator->getTranslation('Catégories duo') ?></i></b></td>
</tr>
<?php
$categoriesHandler = new CategoriesHandler();
$categories = $categoriesHandler->GetIncomeCategoriesForDuo($activeUser->get('userId'));
$i = 1;
foreach ($categories as $category)
{
	$categoryId = $category->get('categoryId');
	$category = $category->get('category');

	?><tr><?php
	if ($category != '')
	{
	?>
	<td>
	<?php echo $category; ?>
	</td>
	<td>
	<input type="text" name="category<?php echo $i; ?>Formula" tabindex="<?php echo $categoryId; ?>" size="12" onkeyup="javascript: CalculateAllAmounts();">&nbsp;=&nbsp; 
	</td>
	<td>
	<input type="text" name="category<?php echo $i; ?>Amount"  tabindex="-1" size="6" readonly> &euro;
	<input type="hidden" name="category<?php echo $i; ?>CategoryId"  tabindex="-1" size="6" readonly value='<?php echo $categoryId; ?>'>
	</td>
	<td align="center"><input type="text" name="category<?= $i ?>ChargeLevel" tabindex="<?= (($i * 2) + 1) ?>" value="50" size="2"> %</td>
	<?php
	}

	$i++;
}
?>
<tr>
<td colspan=4><b><i><?= $translator->getTranslation('Catégories privées de ') ?><?= $activeUser->get('name') ?></i></b></td>
</tr>
<?php
$categories = $categoriesHandler->GetIncomeCategoriesForUser($activeUser->get('userId'));

foreach ($categories as $category)
{
	$categoryId = $category->get('categoryId');
	$category = $category->get('category');

	?><tr><?php
	if ($category != '')
	{
	?>
	<td>
	<?php echo $category; ?>
	</td>
	<td>
	<input type="text" name="category<?php echo $i; ?>Formula" tabindex="<?php echo $categoryId; ?>" size="12" onkeyup="javascript: CalculateAllAmounts();">&nbsp;=&nbsp; 
	</td>
	<td>
	<input type="text" name="category<?php echo $i; ?>Amount"  tabindex="-1" size="6" readonly> &euro;
	<input type="hidden" name="category<?php echo $i; ?>CategoryId"  tabindex="-1" size="6" readonly value='<?php echo $categoryId; ?>'>
	<input type="hidden" name="category<?php echo $i; ?>ChargeLevel"  tabindex="-1" size="6" readonly value='100'>
	</td>
	<?php
	}

	$i++;
}
?>
</table>
</td>

<td style="vertical-align: top;">
<?= $translator->getTranslation('Périodicité:') ?><br/>
<input type="radio" name="periodicity" value="unique" checked><?= $translator->getTranslation('unique') ?></input><br />
<input type="radio" name="periodicity" value="monthly"><?= $translator->getTranslation('tous les mois') ?></input><br />
<?= $translator->getTranslation('pendant') ?> <input type="text" name="periodicityNumber" size="3"> <?= $translator->getTranslation('mois') ?>
</td>

</tr>
</table>
<br />
<input value="<?= $translator->getTranslation('Ajouter') ?>" id="submitForm" type="submit">
<input id="resetForm" name="reset" value="<?= $translator->getTranslation('Effacer') ?>" type="reset">
<input type='button' value='<?= $translator->getTranslation('Annuler') ?>' onclick='LoadRecords();' />
<div id="formResult"></div>
</form>

<script type='text/javascript'>
$("#designation").addClass('search-textbox-label');

$("#designation").autocomplete({
	source: function( request, response ) {
		$.ajax({
			type: 'GET',
			url: "search_designation.php",
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			data: {
					'search_string': request.term,
					'type': 1
				},
			success: function( data ) {
				response( $.map( data.items, function( item ) {
					return {
						label: item
					}
				}));
			},

			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}

		});
	},
	minLength: 0,
	select: function( event, ui ) {
	}
});

$("#designation").focus(function(){
    if(this.value == $(this).attr('title')) {
        this.value = '';
        $(this).removeClass('search-textbox-label');
    }
});

function GetDecimalValue(text) {
	var value = 0; 
	if (!isNaN(parseFloat(text))) {
		text = text.replace(',','.');
		value = parseFloat(text);
	}
	return value;
}

function InterpretMinusFormula(text) {
	var value = 0;

	if (text.length == 0)
		return 0;

	var splits = text.split("-");
	if (splits.length > 0)
		value = GetDecimalValue(splits[0]);
	for (var i = 1; i < splits.length; i++)
		value -= GetDecimalValue(splits[i]);

	return value;
}

function InterpretPlusFormula(text) {
	var value = 0;

	if (text.length == 0)
		return 0;

	var splits = text.split("+");
	for (var i = 0; i < splits.length; i++)
		value += InterpretMinusFormula(splits[i]);
	return value;
}

function InterpretInlineFormula(text) {
	var value = 0;

	if (text.match("--" + "$") == "--") // this will be processed later
		return value;

	value = InterpretPlusFormula(text);
	return value;
}

function InterpretGlobalFormula(text, total) {
	var value = 0;

	if (text.match("--" + "$") == "--")
	{
		var splits = text.split("-");
		if (splits.length > 0)
			value = GetDecimalValue(splits[0]);
		value -= total;
	}

	return value;
}

function CalculateAllAmounts() {
	var value = 0;
	var total = 0;

	for (var i=1;i<<?= $i ?>;i++) {
		if (document.getElementsByName('category'+i+'Formula').length > 0) {
			value = InterpretInlineFormula($("input[name='category"+i+"Formula']").val());
			total += value;
	
			if (value != 0)
				$("input[name='category"+i+"Amount']").val( value );
			else
				$("input[name='category"+i+"Amount']").val('');
		}
	}

	for (i=1;i<<?= $i ?>;i++) {
		if (document.getElementsByName('category'+i+'Formula').length > 0) {
			value = InterpretGlobalFormula($("input[name='category"+i+"Formula']").val(), total);
			total += value;
	
			if (value != 0)
				$("input[name='category"+i+"Amount']").val( value );
		}
	}

	$("input[name='amount']").val(total);
};

</script>