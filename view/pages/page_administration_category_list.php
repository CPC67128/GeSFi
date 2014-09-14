<?php
$type = 'USER';
if (isset($_POST['data']))
	if ($_POST['data'] == 'DUO')
		$type = 'DUO';

$translator = new Translator();

$categoriesHandler = new CategoriesHandler();
if ($type == 'USER')
	$categories = $categoriesHandler->GetCategoriesForUser($_SESSION['user_id']);
else
	$categories = $categoriesHandler->GetCategoriesForDuo($_SESSION['user_id']);
?>
<select id='accountsList<?= $type == 'DUO' ? 'Duo' : '' ?>' name="accountsList<?= $type == 'DUO' ? 'Duo' : '' ?>" size="<?= count($categories) + 1 ?>" onChange="changeForm<?= $type == 'DUO' ? 'Duo' : '' ?>(this)">
<option value="AddCategory" selected>Ajouter une nouvelle catégorie...</option>
<?php
foreach ($categories as $category)
{
	?>
	<option value="<?= $category->get('categoryId') ?>"><?= $category->get('type') == 0 ? $translator->getTranslation("Revenu") : $translator->getTranslation("Dépense") ?> | <?= $category->get('category') ?> (<?= $category->get('sortOrder') ?>)</option>
	<?php
}
?>
</select>