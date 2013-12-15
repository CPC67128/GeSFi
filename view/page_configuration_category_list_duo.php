<?php
include '../security/security_manager.php';

function __autoload($class_name)
{
	$file = '../controller/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../model/'.$class_name . '.php';
	include $file;
}

$translator = new Translator();

$categoryHandler = new CategoryHandler();
$categories = $categoryHandler->GetCategoriesForDuo($_SESSION['user_id']);
?>
<select id="accountsListDuo" name="accountsListDuo" size="<?= count($categories) + 1 ?>" onChange="changeFormDuo(this)">
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