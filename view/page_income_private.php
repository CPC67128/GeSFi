<h1><?= $translator->getTranslation('Saisir un versement') ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>
  <td style="vertical-align: middle;">
	  <?= $translator->getTranslation('Date') ?> <input title="aaaa-mm-jj hh:mm:ss" size="10" id="datePicker" name="date" value="<?php echo date("Y-m-d") ?>">
	  <br/>
  	  <?= $translator->getTranslation('Désignation') ?> <input type="text" name="designation" size="30" value="<?= $translator->getTranslation('Versement') ?>">
  </td>
      <td style="vertical-align: middle;">
    	<table class="categoriesTable">
    	<thead>
    	<td><?= $translator->getTranslation('Catégorie') ?></td>
    	<td><?= $translator->getTranslation('Formule') ?></td>
    	<td><?= $translator->getTranslation('Montant') ?></td>
    	</thead>	
<?php
$categories = $activeAccount->GetUserCategoriesForIncome();
$i = 1;
while ($row = $categories->fetch())
{
	$categoryId = $row['category_id'];
	$category = $row['category'];

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
	<?php
	}

	$i++;
}
?>
		<tfoot>
    	<td>Total</td>
		<td><font size=1>x-- = x - others<br />x+y-z</font></td>
		<td><input type="text" name="totalAmount" tabindex="-1" size="6" readonly>&nbsp;&euro;</td>
    	<td></td>
    	</tfoot>
	</table>
	</td>
  <td style="vertical-align: middle;">
	  <?= $translator->getTranslation('Périodicité:') ?>
	  <br/>
	  <input type="radio" name="periodicity" value="unique" checked><?= $translator->getTranslation('unique') ?></input>
	  <br />
	  <input type="radio" name="periodicity" value="monthly"><?= $translator->getTranslation('tous les mois') ?></input>
	  <br />
	  <?= $translator->getTranslation('pendant') ?> <input type="text" name="PeriodicityNumber" size="3"> <?= $translator->getTranslation('mois') ?>
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

	$("input[name='totalAmount']").val(total);
};

</script>