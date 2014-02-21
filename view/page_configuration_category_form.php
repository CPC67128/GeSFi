<?php
include '../security/security_manager.php';

$translator = new Translator();
?>
<div id='formPlaceHolder'>
<form action="/" id="form">
<?php
if ($_POST['categoryId'] == 'AddCategory')
{
?>
<?= $translator->getTranslation('Identifiant') ?> <input name='categoryId' type='text' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="" /><br />
<?= $translator->getTranslation('Nom') ?> <input name='category' type='text' size='41' value="" /><br /> 
<?= $translator->getTranslation('Type') ?> <select name="type">
<option value="0">Revenu</option>
<option value="1">Dépense</option>
</select><br />
<?= $translator->getTranslation('Ordre') ?> <input name='sortOrder' type='text' size='7' value="" /><br /><br />
<?php
}
else
{
	$categoryHandler = new CategoryHandler();
	$category = $categoryHandler->GetCategory($_POST['categoryId']);
?>
<?= $translator->getTranslation('Identifiant') ?> <input type='text' name='categoryId' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $category->getCategoryId() ?>" /><br /> 
<?= $translator->getTranslation('Nom') ?> <input type='text' name='category' size='41' value="<?= $category->getCategory() ?>" /><br /> 
<?= $translator->getTranslation('Type') ?> <input name="type" type='hidden' value="<?= $category->getType() ?>" /><input type='text' size='15' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $category->getType() == 0 ? $translator->getTranslation("Revenu") : $translator->getTranslation("Dépense") ?>" /><br />
<?= $translator->getTranslation('Active depuis') ?> <input name='activeFrom' type='text' size='20' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $category->getActiveFrom() ?>" /><br />
<?= $translator->getTranslation('Ordre') ?> <input name='sortOrder' type='text' size='7' value="<?= $category->getSortOrder() ?>" /><br />
<br />
<font color='red'><?= $translator->getTranslation('Inactiver') ?> <input name='delete' type='checkbox' /></font> <i>Cocher pour inactiver la catégorie</i><br /><br />
<?php
}
?>
<input type="submit" id='submitForm' value="Envoyer" />
</div>
<div id='formResult'></div>
</form>

<script type='text/javascript'>
$("#form").submit( function () {
	document.getElementById("submitForm").disabled = true;
	$.post (
		'../controller/controller.php?action=category_modification_user',
		$(this).serialize(),
		function(response, status) {
			$("#formResult").stop().show();
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					$("#formResult").html(response);
				}
				else {
					$("#formResult").html(response);
					$("#formPlaceHolder").html('');
					listCategories();
				}
			}
			else {
				$("#formResult").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitForm").disabled = false;

			setTimeout(function() {
				$("#formResult").fadeOut("slow", function () {
					$('#formResult').empty();
				})
			}, 4000);
		}
	);
	return false;
});
</script>