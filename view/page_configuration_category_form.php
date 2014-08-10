<?php
include '../security/security_manager.php';

$type = 'USER';
if (isset($_GET['type']))
if ($_GET['type'] == 'DUO')
	$type = 'DUO';

$translator = new Translator();
?>
<div id='form<?= $type == 'DUO' ? 'Duo' : '' ?>PlaceHolder'>
<form action="/" id="form<?= $type == 'DUO' ? 'Duo' : '' ?>">
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
<?= $translator->getTranslation('Identifiant') ?> <input type='text' name='categoryId' size='41' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $category->get("categoryId") ?>" /><br /> 
<?= $translator->getTranslation('Nom') ?> <input type='text' name='category' size='41' value="<?= $category->get('category') ?>" /><br /> 
<?= $translator->getTranslation('Type') ?> <input name="type" type='hidden' value="<?= $category->get('type') ?>" /><input type='text' size='15' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $category->get('type') == 0 ? $translator->getTranslation("Revenu") : $translator->getTranslation("Dépense") ?>" /><br />
<?= $translator->getTranslation('Active depuis') ?> <input name='activeFrom' type='text' size='20' style='background-color : #d1d1d1;' readonly="readonly" value="<?= $category->get("activeFrom") ?>" /><br />
<?= $translator->getTranslation('Ordre') ?> <input name='sortOrder' type='text' size='7' value="<?= $category->get("sortOrder") ?>" /><br />
<br />
<font color='red'><?= $translator->getTranslation('Inactiver') ?> <input name='inactive' type='checkbox' <?= $category->get("markedAsInactive") ? "checked" : "" ?> /></font> <i>Cocher pour inactiver la catégorie</i><br /><br />
<?php
}
?>
<input type="submit" id='submitForm<?= $type == 'DUO' ? 'Duo' : '' ?>' value="Envoyer" />
</div>
<div id='form<?= $type == 'DUO' ? 'Duo' : '' ?>Result'></div>
</form>

<script type='text/javascript'>
$("#form<?= $type == 'DUO' ? 'Duo' : '' ?>").submit( function () {
	document.getElementById("submitForm<?= $type == 'DUO' ? 'Duo' : '' ?>").disabled = true;
	$.post (
		'../controller/controller.php?action=category_modification_user',
		$(this).serialize(),
		function(response, status) {
			$("#formResult<?= $type == 'DUO' ? 'Duo' : '' ?>").stop().show();
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					$("#form<?= $type == 'DUO' ? 'Duo' : '' ?>Result").html(response);
				}
				else {
					$("#form<?= $type == 'DUO' ? 'Duo' : '' ?>Result").html(response);
					$("#form<?= $type == 'DUO' ? 'Duo' : '' ?>PlaceHolder").html('');
					listCategories<?= $type == 'DUO' ? 'Duo' : '' ?>();
				}
			}
			else {
				$("#form<?= $type == 'DUO' ? 'Duo' : '' ?>Result").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitForm<?= $type == 'DUO' ? 'Duo' : '' ?>").disabled = false;

			setTimeout(function() {
				$("#form<?= $type == 'DUO' ? 'Duo' : '' ?>Result").fadeOut("slow", function () {
					$('#form<?= $type == 'DUO' ? 'Duo' : '' ?>Result').empty();
				})
			}, 4000);
		}
	);
	return false;
});
</script>