<h1><?= $translator->getTranslation("Catégories de l'utilisateur") ?></h1>

<table class="actionsTable">
<tr>
<td>
<div id="categoryList">
</div>
</td>
<td style="vertical-align: top;">
<div id="categoryModification">
Sélectionnez une action ou une catégorie...
</div>
</td>
</tr>
</table>

<h1><?= $translator->getTranslation("Catégories du couple") ?></h1>

<table class="actionsTable">
<tr>
<td>
<div id="categoryDuoList">
</div>
</td>
<td style="vertical-align: top;">
<div id="categoryDuoModification">
Sélectionnez une action ou une catégorie...
</div>
</td>
</tr>
</table>

<script type="text/javascript">
listCategories();
listCategoriesDuo();

function listCategories()
{
	$.ajax({
	    type : 'POST',
	    url : 'page_configuration_category_list.php',
	    dataType: 'html',
	    success : function(data) {
	        $('#categoryList').html(data);
	    }
	});
}

function listCategoriesDuo()
{
	$.ajax({
	    type : 'POST',
	    url : 'page_configuration_category_list_duo.php',
	    dataType: 'html',
	    success : function(data) {
	        $('#categoryDuoList').html(data);
	    }
	});
}

function changeForm(categoriesList)
{
  var idx = categoriesList.selectedIndex;
  var value = categoriesList.options[idx].value;

  if (value == '')
	  $('#categoryModification').html('');
  else
	  $.ajax({
	      type : 'POST',
	      url : 'page_configuration_category_form.php',
	      data: { categoryId: value }, 
	      dataType: 'html',
	      success : function(data) {
	          $('#categoryModification').html(data);
	      }
	  });
}

function changeFormDuo(categoriesList)
{
  var idx = categoriesList.selectedIndex;
  var value = categoriesList.options[idx].value;

  if (value == '')
	  $('#categoryDuoModification').html('');
  else
	  $.ajax({
	      type : 'POST',
	      url : 'page_configuration_category_form_duo.php',
	      data: { categoryId: value }, 
	      dataType: 'html',
	      success : function(data) {
	          $('#categoryDuoModification').html(data);
	      }
	  });
}
</script>