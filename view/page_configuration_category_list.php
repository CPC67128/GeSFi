<?php
include '../security/security_manager.php';

$type = 'USER';
if (isset($_GET['type']))
	if ($_GET['type'] == 'DUO')
		$type = 'DUO';

$translator = new Translator();

$categoryHandler = new CategoryHandler();
if ($type == 'USER')
	$categories = $categoryHandler->GetCategoriesForUser($_SESSION['user_id']);
else
	$categories = $categoryHandler->GetCategoriesForDuo($_SESSION['user_id']);
?>
<select id='accountsList<?= $type == 'DUO' ? 'Duo' : '' ?>' name="accountsList<?= $type == 'DUO' ? 'Duo' : '' ?>" size="<?= count($categories) + 1 ?>" onChange="changeForm<?= $type == 'DUO' ? 'Duo' : '' ?>(this)">
<option value="AddCategory" selected>Ajouter une nouvelle catégorie...</option>
<?php
foreach ($categories as $category)
{
?>
<option value="<?= $category->getCategoryId() ?>"><?= $category->getType() == 0 ? $translator->getTranslation("Revenu") : $translator->getTranslation("Dépense") ?> | <?= $category->getCategory() ?> (<?= $category->getSortOrder() ?>)</option>
<?php
}
?>
</select>