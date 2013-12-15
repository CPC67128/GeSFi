<h1><?= $translator->getTranslation('Graphiques') ?></h1>

<form action="/" id="form">
<?php
$accountsManager = new AccountsManager();
$accounts = $accountsManager->GetAllInvestmentAccounts();
$i = 0;

foreach ($accounts as $account)
{
?>
<input type="checkbox" name="account<?= $i ?>" value="<?= $account->get('accountId') ?>"><?= $account->get('name') ?><?= strlen($account->get('description')) > 0 ? ' ('.$account->get('description').')' : ''  ?>
<br />
<?php
	$i++;
}
?>
<input type='hidden' name="maximumIndex" value="<?= $i ?>" />
<input type="submit" id='submitForm' value="Rafraîchir" />
</form>

<div id='graph'>
<img src='page_statistics_investment_global_graph_yield.php<?= '?guid='.md5(uniqid(rand(),true)) ?>' />
</div>

<script type='text/javascript'>
$("#form").submit( function () {
	document.getElementById("submitForm").disabled = true;
	$('#graph').html('<img src="../media/loading.gif" />');
	$.post (
		'page_statistics_investment_global_graph_controller.php',
		$(this).serialize(),
		function(response, status) {
			if (status == 'success') {
				$("#graph").html(response);
			}
			else {
				$("#graph").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitForm").disabled = false;
		}
	);
	return false;
});
</script>