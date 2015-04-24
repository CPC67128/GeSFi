<h1><?= $translator->getTranslation('Gestion des utilisateurs') ?></h1>

<table class="actionsTable">
<tr>
<td style="vertical-align: top;">
<div id="usersList">
</div>
</td>
<td style="vertical-align: top;">
<div id="userModification">
</div>
</td>
</tr>
</table>

<script type="text/javascript">
listUsers();

function listUsers()
{
	$.ajax({
	    type : 'POST',
	    url : 'page.php',
	    data: {
	        'page': 'administration_user_list', 
	        'area': 'administration'
	    },
	    dataType: 'html',
	    success : function(data) {
	        $('#usersList').html(data);
	        $('#userModification').html('Sélectionnez un utilisateur...');
	    }
	});
}

function changeUser(usersList)
{
  var idx = usersList.selectedIndex;
  var value = usersList.options[idx].value;

  if (value == '')
	  $('#userModification').html('');
  else
	  $.ajax({
	      type : 'POST',
		    url : 'page.php',
		    data: {
		        'page': 'administration_user_form', 
		        'area': 'administration',
		        userId: value
		    },
	      dataType: 'html',
	      success : function(data) {
	          $('#userModification').html(data);
	      }
	  });
}
</script>

