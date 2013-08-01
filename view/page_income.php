<h1><?= $translator->getTranslation('Saisir un versement') ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>
  <td style="vertical-align: middle;">

<?= $translator->getTranslation('Vers le compte :') ?><br/>

<br/>

<?php
$accounts = $accountsManager->GetAllDuoAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="toAccount" <?= $account->getAccountId() == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->getAccountId() ?>"><?= $account->getName() ?><br />
<?php } ?>

<br/>

<input type="radio" name="toAccount" value="USER/<?= $activeUser->getUserId() ?>"><i><?= $activeUser->getName() ?> / Compte inconnu</i><br />
<?php
$accounts = $accountsManager->GetAllPrivateAccounts();
foreach ($accounts as $account)
{ ?>
<input type="radio" name="toAccount" <?= $account->getAccountId() == $_SESSION['account_id'] ? 'checked' : '' ?> value="<?= $account->getAccountId() ?>"><?= $activeUser->getName() ?> / <?= $account->getName() ?><br />
<?php } ?>


<br/>
<?= $translator->getTranslation('Date') ?> <input title="aaaa-mm-jj hh:mm:ss" size="10" id="datePicker" name="date" value="<?php echo date("Y-m-d") ?>"><br/>
<?= $translator->getTranslation('Montant') ?> <input type="text" name="totalAmount" style='background-color : #d1d1d1;' tabindex="-1" size="6" readonly>&nbsp;&euro;<br/>
<?= $translator->getTranslation('Désignation') ?> <input type="text" name="designation" id="designation" size="30" value="<?= $translator->getTranslation('Versement sur compte commun') ?>">
</td>

<td style="vertical-align: middle;">
<table class="categoriesTable">
<thead>
<td><?= $translator->getTranslation('Catégorie') ?></td>
<td><?= $translator->getTranslation('Formule') ?></td>
<td><?= $translator->getTranslation('Montant') ?></td>
</thead>
<thead>
<td>&nbsp;</td>
<td><font size=1>x-- = x - others<br />x+y-z</font></td>
<td>&nbsp;</td>
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
</table>
</td>

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
<script type="text/javascript">
function onDestinationChange(destination)
{
	if (destination == 'duo')
		document.getElementById("designation").value = '<?= $translator->getTranslation('Versement sur compte commun') ?>';
	else
		document.getElementById("designation").value = '<?= $translator->getTranslation('Versement au partenaire') ?>';
}
</script>