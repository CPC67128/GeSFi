<h1><?= $translator->getTranslation('Déclarer une dépense') ?></h1>

<form action="/" id="form">
<table class="actionsTable">
<tr>
<td style="vertical-align: top;">

<?= $translator->getTranslation('Depuis le compte :') ?><br/>

<br/>

<?php
$accounts = $accountsHandler->GetAllDuoAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $activeAccount->get('accountId') ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br />
<?php } ?>
<?php
$accounts = $accountsHandler->GetAllSharedLoans();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $activeAccount->get('accountId') ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br />
<?php } ?>
&nbsp;&nbsp;<?= $translator->getTranslation('effectuée par') ?><input type="radio" name="userId" value="<?= $activeUser->get('userId') ?>" checked><?= $activeUser->get('name') ?> </input><?= $translator->getTranslation('ou'); ?> <input type="radio" name="userId" value="<?= $activeUser->GetPartnerId() ?>"><?= $activeUser->GetPartnerName() ?></input><br/>

<br/>

<input type="radio" name="fromAccount" value="USER/<?= $activeUser->get('userId') ?>"><i><?= $activeUser->get('name') ?> / Compte inconnu</i><br />
<?php
$accounts = $accountsHandler->GetAllPrivateAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $activeAccount->get('accountId') ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $activeUser->get('name') ?> / <?= $account->get('name') ?><br />
<?php } ?>

<br/>

<input type="radio" name="fromAccount" value="USER/<?= $activeUser->GetPartnerId() ?>"><i><?= $activeUser->GetPartnerName() ?> / Compte inconnu</i><br />

<br/>

<?= $translator->getTranslation('Date') ?> <input type="hidden" id="datePickerHidden" name="date" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline"></div><br/>

Montant <input type="text" name="amount" tabindex="-1" size="6" style='background-color : #d1d1d1;' readonly>&nbsp;&euro;<br />

<?= $translator->getTranslation('Désignation') ?> <input class="ui-autocomplete-input" type="text" name="designation" id="designation" size="30"><br/>
<br/>
<?= $translator->getTranslation("Confirmer l'opération") ?> <input type="checkbox" name="confirmed" id="confirmed" />


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
$categories = $categoriesHandler->GetOutcomeCategoriesForDuo($activeUser->get('userId'));
$i = 1;
foreach ($categories as $category)
{
	$categoryId = $category->get('categoryId');
	$category = $category->get('category');
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
<td colspan=4><b><i><?= $translator->getTranslation('Catégories privées de ') ?><?= $activeUser->get('name') ?></i></b></td>
</tr>
<?php
$categories = $categoriesHandler->GetOutcomeCategoriesForUser($activeUser->get('userId'));

foreach ($categories as $category)
{
	$categoryId = $category->get('categoryId');
	$category = $category->get('category');
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
<td><input type="text" name="category<?= $i ?>Amount"  tabindex="-1" size="6" readonly> &euro;<input type="hidden" name="category<?= $i ?>CategoryId" tabindex="-1" size="6" readonly value='USER/<?= $activeUser->get('userId') ?>'></td>
<td align="center"><input type="text" name="category<?= $i ?>ChargeLevel" tabindex="<?= (($i * 2) + 1) ?>" value="100" size="2"> %</td>
<?php $i++; ?>
</tr>

<td colspan=4><b><i><?= $translator->getTranslation('Catégories privées de ') ?><?= $activeUser->GetPartnerName() ?></i></b></td>
</tr>
<?php
$categoriesHandler = new CategoriesHandler();
$categories = $categoriesHandler->GetOutcomeCategoriesForUser($activeUser->GetPartnerId());

foreach ($categories as $category)
{
	$categoryId = $category->get('categoryId');
	$category = $category->get('category');
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
$("#designation").autocomplete({
	source: function( request, response ) {
		$.ajax({
			type: 'GET',
			url: "search_designation.php",
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			data: {
					'search_string': request.term,
					'type': 2
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

function CalculateAllAmounts() {
	var value = 0;
	var total = 0;

	for (var i=1;i<=60;i++) { // TODO 60 to replace with proper search
		if (document.getElementsByName('category'+i+'Formula').length > 0) {
			value = InterpretInlineFormula($("input[name='category"+i+"Formula']").val());
			total += value;
	
			if (value != 0)
				$("input[name='category"+i+"Amount']").val( value );
			else
				$("input[name='category"+i+"Amount']").val('');
		}
	}

	for (i=1;i<=60;i++) { // TODO 60 to replace with proper search
		if (document.getElementsByName('category'+i+'Formula').length > 0) {
			value = InterpretGlobalFormula($("input[name='category"+i+"Formula']").val(), total);
			total += value;
	
			if (value != 0)
				$("input[name='category"+i+"Amount']").val( value );
		}
	}

	$("input[name='amount']").val(total);
}


</script>