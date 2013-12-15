<h1><?= $translator->getTranslation('Déclarer une dépense') ?></h1>

<form action="/" id="form">
<table class="actionsTable">
<tr>
<td style="vertical-align: middle;">

<?= $translator->getTranslation('Depuis le compte :') ?><br/>

<br/>

<?php
$accounts = $accountsManager->GetAllDuoAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="fromAccount" <?= $account->get('type') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br />
<?php } ?>

&nbsp;&nbsp;<?= $translator->getTranslation('effectuée par') ?><input type="radio" name="userId" value="<?= $activeUser->get('userId') ?>" checked><?= $activeUser->get('name') ?> </input><?= $translator->getTranslation('ou'); ?> <input type="radio" name="userId" value="<?= $activeUser->GetPartnerId() ?>"><?= $activeUser->GetPartnerName() ?></input><br/>

<br/>

<input type="radio" name="fromAccount" value="USER/<?= $activeUser->getUserId() ?>"><i><?= $activeUser->getName() ?> / Compte inconnu</i><br />
<?php
$accounts = $accountsManager->GetAllPrivateAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $activeUser->get('name') ?> / <?= $account->get('name') ?><br />
<?php } ?>

<br/>

<input type="radio" name="fromAccount" value="USER/<?= $activeUser->GetPartnerId() ?>"><i><?= $activeUser->GetPartnerName() ?> / Compte inconnu</i><br />

<br/>

<?= $translator->getTranslation('Date') ?> <input title="aaaa-mm-jj hh:mm:ss" size="10" id="datePicker" name="date" value="<?php echo date("Y-m-d") ?>"><br/>
Montant <input type="text" name="amount" tabindex="-1" size="6" style='background-color : #d1d1d1;' readonly>&nbsp;&euro;<br />
<?= $translator->getTranslation('Désignation') ?> <input type="text" name="designation" size="30">
</td>

<td style="vertical-align: middle;">

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
$categories = $activeAccount->GetDuoCategoriesForOutcome();
$i = 1;
while ($row = $categories->fetch())
{
	$categoryId = $row['category_id'];
	$category = $row['category'];
?>
<tr>
<td><?= $category ?></td>
<td><input type="text" name="category<?= $i ?>Formula" tabindex="<?= ($i * 2) ?>" size="12" onkeyup="javascript: CalculateAllAmounts();">&nbsp;=&nbsp;</td>
<td><input type="text" name="category<?= $i ?>Amount"  tabindex="-1" size="6" readonly> &euro;<input type="hidden" name="category<?= $i ?>CategoryId" tabindex="-1" size="6" readonly value='<?= $categoryId ?>'></td>
<td align="center"><input type="text" name="category<?= $i ?>ChargeLevel" tabindex="<?= (($i * 2) + 1) ?>" value="50" size="2"> %</td>
</tr>
<?php
	$i++;
}
?>
<tr>

<td colspan=4><b><i><?= $translator->getTranslation('Catégories privées de ') ?><?= $activeUser->getName() ?></i></b></td>
</tr>
<?php
$categoriesHandler = new CategoryHandler();
$categories = $categoriesHandler->GetOutcomeCategoriesForUser($activeUser->getUserId());

foreach ($categories as $category)
{
	$categoryId = $category->getCategoryId();
	$category = $category->getCategory();
	?>
<tr>
<td><?= $category ?></td>
<td><input type="text" name="category<?= $i ?>Formula" tabindex="<?= ($i * 2) ?>" size="12" onkeyup="javascript: CalculateAllAmounts();">&nbsp;=&nbsp;</td>
<td><input type="text" name="category<?= $i ?>Amount"  tabindex="-1" size="6" readonly> &euro;<input type="hidden" name="category<?= $i ?>CategoryId" tabindex="-1" size="6" readonly value='<?= $categoryId ?>'></td>
<td align="center"><input type="text" name="category<?= $i ?>ChargeLevel" tabindex="<?= (($i * 2) + 1) ?>" value="100" size="2"> %</td>
</tr>
<?php
	$i++;
}
?>
<tr>
<td><i><?= $translator->getTranslation('(Inconnue)') ?></i></td>
<td><input type="text" name="category<?= $i ?>Formula" tabindex="<?= ($i * 2) ?>" size="12" onkeyup="javascript: CalculateAllAmounts();">&nbsp;=&nbsp;</td>
<td><input type="text" name="category<?= $i ?>Amount"  tabindex="-1" size="6" readonly> &euro;<input type="hidden" name="category<?= $i ?>CategoryId" tabindex="-1" size="6" readonly value='USER/<?= $activeUser->getUserId() ?>'></td>
<td align="center"><input type="text" name="category<?= $i ?>ChargeLevel" tabindex="<?= (($i * 2) + 1) ?>" value="100" size="2"> %</td>
<?php $i++; ?>
</tr>

<td colspan=4><b><i><?= $translator->getTranslation('Catégories privées de ') ?><?= $activeUser->GetPartnerName() ?></i></b></td>
</tr>
<?php
$categoriesHandler = new CategoryHandler();
$categories = $categoriesHandler->GetOutcomeCategoriesForUser($activeUser->GetPartnerId());

foreach ($categories as $category)
{
	$categoryId = $category->getCategoryId();
	$category = $category->getCategory();
	?>
<tr>
<td><?= $category ?></td>
<td><input type="text" name="category<?= $i ?>Formula" tabindex="<?= ($i * 2) ?>" size="12" onkeyup="javascript: CalculateAllAmounts();">&nbsp;=&nbsp;</td>
<td><input type="text" name="category<?= $i ?>Amount"  tabindex="-1" size="6" readonly> &euro;<input type="hidden" name="category<?= $i ?>CategoryId" tabindex="-1" size="6" readonly value='<?= $categoryId ?>'></td>
<td align="center"><input type="text" name="category<?= $i ?>ChargeLevel" tabindex="<?= (($i * 2) + 1) ?>" value="0" size="2"> %</td>
</tr>
<?php
	$i++;
}
?>
<tr>
<td><i><?= $translator->getTranslation('(Inconnue)') ?></i></td>
<td><input type="text" name="category<?= $i ?>Formula" tabindex="<?= ($i * 2) ?>" size="12" onkeyup="javascript: CalculateAllAmounts();">&nbsp;=&nbsp;</td>
<td><input type="text" name="category<?= $i ?>Amount"  tabindex="-1" size="6" readonly> &euro;<input type="hidden" name="category<?= $i ?>CategoryId" tabindex="-1" size="6" readonly value='USER/<?= $activeUser->GetPartnerId() ?>'></td>
<td align="center"><input type="text" name="category<?= $i ?>ChargeLevel" tabindex="<?= (($i * 2) + 1) ?>" value="0" size="2"> %</td>
<?php $i++; ?>
</tr>

</table>

<td style="vertical-align: middle;">
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
/**
$("input[name='actor']").click(function() {
	if ($("input[name='actor']:checked").val() == 1) {
		for (var i=1;i<=<?= $i-1 ?>;i++) {
			$("input[name='category"+i+"ChargeLevel']").val('50');
		}
	}
	else {
		for (var i=1;i<=<?= $i-1 ?>;i++) {
			$("input[name='category"+i+"ChargeLevel']").val('50');
		}
	}
});
*/
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

	for (var i=1;i<=<?= $i-1 ?>;i++) {
		if (document.getElementsByName('category'+i+'Formula').length > 0) {
			value = InterpretInlineFormula($("input[name='category"+i+"Formula']").val());
			total += value;
	
			if (value != 0)
				$("input[name='category"+i+"Amount']").val( value );
			else
				$("input[name='category"+i+"Amount']").val('');
		}
	}

	for (i=1;i<=<?= $i-1 ?>;i++) {
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