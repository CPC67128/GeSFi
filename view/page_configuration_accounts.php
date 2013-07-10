<h1><?= $translator->getTranslation('Gestion des comptes') ?></h1>

<table class="actionsTable">
<tr>
<td style="vertical-align: top;">
<div id="accountsList">
</div>
</td>
<td style="vertical-align: top;">
<div id="accountModification">
Sélectionnez une action ou un compte...
</div>
</td>
</tr>
</table>

<script type="text/javascript">
listAccounts();

function listAccounts()
{
	$.ajax({
	    type : 'POST',
	    url : 'page_configuration_accounts_list.php',
	    dataType: 'html',
	    success : function(data) {
	        $('#accountsList').html(data);
	    }
	});
}

function changeAccount(accountsList)
{
  var idx = accountsList.selectedIndex;
  var value = accountsList.options[idx].value;

  if (value == '')
	  $('#accountModification').html('');
  else
	  $.ajax({
	      type : 'POST',
	      url : 'page_configuration_accounts_form.php',
	      data: { accountId: value }, 
	      dataType: 'html',
	      success : function(data) {
	          $('#accountModification').html(data);
	      }
	  });
}
</script>

