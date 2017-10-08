<h1><?= t('Saisir un versement') ?></h1>

<form id="form" action="/">
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
$i = 1;
$categories = $categoriesHandler->GetIncomeCategoriesForDuo($activeUser->get('userId'));
foreach ($categories as $category) {
	DisplayCategorieLine($category);
}
?>
<tr>
<td colspan=4><b><i><?= t('Catégories privées de ') ?><?= $activeUser->get('name') ?></i></b></td>
</tr>
<?php
$categories = $categoriesHandler->GetIncomeCategoriesForUser($activeUser->get('userId'));
foreach ($categories as $category) {
	DisplayCategorieLine($category, "100", true);
}
?>
</table>
</td>

<td>
<?= t('Vers le compte :') ?><br>
<br>
<?php
$accounts = $accountsHandler->GetAllDuoAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="toAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><br>
<?php } ?>
<br>
<input type="radio" name="toAccount" value="USER/<?= $activeUser->get('userId') ?>"><i><?= $activeUser->get('name') ?> / Compte inconnu</i><br>
<?php
$accounts = $accountsHandler->GetAllPrivateAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="toAccount" <?= $account->get('accountId') == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->get('accountId') ?>"><?= $activeUser->get('name') ?> / <?= $account->get('name') ?><br>
<?php } ?>
<br>
<?= t('Date') ?> <input type="hidden" id="datePickerHidden" name="date" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline"></div><br>
<?= t('Montant') ?> <input type="text" name="amount" style='background-color : #d1d1d1;' tabindex="-1" size="6" readonly>&nbsp;&euro;<br>
<?= t('Désignation') ?> <input type="text" name="designation" id="designation" size="30" ><br>
<br>
<?= t("Confirmer l'opération") ?> <input type="checkbox" name="confirmed" id="confirmed" />
<?php AddFormButton(); ?>
</td>

<td style="vertical-align: top;">
<?= t('Périodicité:') ?><br>
<input type="radio" name="periodicity" value="unique" checked><?= t('unique') ?></input><br>
<input type="radio" name="periodicity" value="monthly"><?= t('tous les mois') ?></input><br>
<?= t('pendant') ?> <input type="text" name="periodicityNumber" size="3"> <?= t('mois') ?>
</td>

</tr>
</table>
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

</script>