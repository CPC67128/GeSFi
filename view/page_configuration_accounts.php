<h1><?= $translator->getTranslation('Gestion des comptes') ?></h1>

<select name="accountsList" onChange="changeAccount(this)">
<option value="">Choisissez votre compte ou votre action...</option>
<option value="AddAccount">Ajouter un nouveau compte...</option>
<?php
$accountsManager = new AccountsManager();
$accounts = $accountsManager->GetAllAccounts();

foreach ($accounts as $account)
{
?>
<option value="<?= $account->getAccountId() ?>"><?= $account->getName() ?></option>
<?php
}
?>
</select> 

<br />
<br />
<div id="accountModification">
</div>

<script type="text/javascript">
function changeAccount(accountsList)
{
  var idx = accountsList.selectedIndex;
  var value = accountsList.options[idx].value;

  if (value == '')
	  $('#accountModification').html('');
  else
	  $.ajax({
	      type : 'POST',
	      url : 'page_configuration_accounts__form.php',
	      data: { accountId: value }, 
	      dataType: 'html',
	      success : function(data) {
	          $('#accountModification').html(data);
	      }
	  });
}
</script>

