<h1><?= t('Déclarer une dépense') ?></h1>

<form action="/" id="form">
<table class="actionsTable">
<tr>

<td>
<table class="categoriesTable">
<thead>
<td><?= t('Catégorie') ?></td>
<td><?= t('Formule') ?></td>
<td><?= t('Montant') ?></td>
<td><?= t('Prise en charge') ?></td>
</thead>
<thead>
<td>&nbsp;</td>
<td><font size=1>x-- = x - others<br>x+y-z</font></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</thead>
<tr>

<td colspan=4><b><i><?= t('Catégories duo') ?></i></b></td>
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
<td colspan=4><b><i><?= t('Catégories privées de ') ?><?= $activeUser->get('name') ?></i></b></td>
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
<td><i><?= t('(Inconnue)') ?></i></td>
<td><input type="text" name="category<?= $i ?>Formula" tabindex="<?= ($i * 2) ?>" size="12" onkeyup="javascript: CalculateAllAmounts();">&nbsp;=&nbsp;</td>
<td><input type="text" name="category<?= $i ?>Amount"  tabindex="-1" size="6" readonly> &euro;<input type="hidden" name="category<?= $i ?>CategoryId" tabindex="-1" size="6" readonly value='USER/<?= $activeUser->get('userId') ?>'></td>
<td align="center"><input type="text" name="category<?= $i ?>ChargeLevel" tabindex="<?= (($i * 2) + 1) ?>" value="100" size="2"> %</td>
<?php $i++; ?>
</tr>

<td colspan=4><b><i><?= t('Catégories privées de ') ?><?= $activeUser->GetPartnerName() ?></i></b></td>
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
<td><i><?= t('(Inconnue)') ?></i></td>
<td><input type="text" name="category<?= $i ?>Formula" tabindex="<?= ($i * 2) ?>" size="12" onkeyup="javascript: CalculateAllAmounts();">&nbsp;=&nbsp;</td>
<td><input type="text" name="category<?= $i ?>Amount"  tabindex="-1" size="6" readonly> &euro;<input type="hidden" name="category<?= $i ?>CategoryId" tabindex="-1" size="6" readonly value='USER/<?= $activeUser->GetPartnerId() ?>'></td>
<td align="center"><input type="text" name="category<?= $i ?>ChargeLevel" tabindex="<?= (($i * 2) + 1) ?>" value="0" size="2"> %</td>
<?php $i++; ?>
</tr>

</table>



<td>
<?= t('Depuis le compte :') ?><br>
<br>
<?php
$accounts = $accountsHandler->GetAllDuoAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $activeAccount->get('accountId') ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br>
<?php } ?>
<?php
$accounts = $accountsHandler->GetAllSharedLoans();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $activeAccount->get('accountId') ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br>
<?php } ?>
&nbsp;&nbsp;<?= t('effectuée par') ?><input type="radio" name="userId" value="<?= $activeUser->get('userId') ?>" checked><?= $activeUser->get('name') ?> </input><?= t('ou'); ?> <input type="radio" name="userId" value="<?= $activeUser->GetPartnerId() ?>"><?= $activeUser->GetPartnerName() ?></input><br>
<br>
<input type="radio" name="fromAccount" value="USER/<?= $activeUser->get('userId') ?>"><i><?= $activeUser->get('name') ?> / Compte inconnu</i><br>
<?php
$accounts = $accountsHandler->GetAllPrivateAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="fromAccount" <?= $account->get('accountId') == $activeAccount->get('accountId') ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $activeUser->get('name') ?> / <?= $account->get('name') ?><br>
<?php } ?>
<br>
<input type="radio" name="fromAccount" value="USER/<?= $activeUser->GetPartnerId() ?>"><i><?= $activeUser->GetPartnerName() ?> / Compte inconnu</i><br>
<br>
<?= t('Date') ?> <input type="hidden" id="datePickerHidden" name="date" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline"></div><br>

Montant <input type="text" name="amount" tabindex="-1" size="6" style='background-color : #d1d1d1;' readonly>&nbsp;&euro;<br>

<?= t('Désignation') ?> <input class="ui-autocomplete-input" type="text" name="designation" id="designation" size="30"><br>
<br>
<?= t("Confirmer l'opération") ?> <input type="checkbox" name="confirmed" id="confirmed" />
<?php AddFormButton(); ?>
</td>




<td>
<?= t('Périodicité:') ?><br>
<input type="radio" name="periodicity" value="unique" checked><?= t('unique') ?></input><br>
<input type="radio" name="periodicity" value="monthly"><?= t('tous les mois') ?></input><br>
<?= t('pendant') ?> <input type="text" name="periodicityNumber" size="3"> <?= t('mois') ?>
</td>
</tr>

</table>
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


</script>