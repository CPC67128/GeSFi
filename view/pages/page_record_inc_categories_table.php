<table class="categoriesTable">
<thead><td><?= t("Catégorie") ?></td><td><?= t("Formule") ?></td><td><?= t("Montant") ?></td><td><?= t("Prise en charge") ?></td></thead>
<thead><td></td><td><font size=1>x-- = x - others<br>x+y-z</font></td><td></td><td></td></thead>
<tr>
<td colspan=4><b><i><?= t("Catégories duo") ?></i></b></td>
</tr>
<?php
$i = 1;
if ($page == "record_payment")
	$categories = $categoriesHandler->GetOutcomeCategoriesForDuo($activeUser->get("userId"));
else
	$categories = $categoriesHandler->GetIncomeCategoriesForDuo($activeUser->get("userId"));
foreach ($categories as $category) {
	DisplayCategorieLine($category);
}
?>
<tr>
<td colspan=4><b><i><?= t("Catégories privées de ") ?><?= $activeUser->get("name") ?></i></b></td>
</tr>
<?php
if ($page == "record_payment")
	$categories = $categoriesHandler->GetOutcomeCategoriesForUser($activeUser->get("userId"));
else
	$categories = $categoriesHandler->GetIncomeCategoriesForUser($activeUser->get("userId"));

foreach ($categories as $category) {
	if ($page == "'record_payment") 
		DisplayCategorieLine($category, "100");
	else
		DisplayCategorieLine($category, "100", false);
}

if ($page == "record_payment") {
	$category = new Category();
	$category->set("categoryId", 'USER/'.$activeUser->get("userId"));
	$category->set("category", t("(Inconnue)"));
	DisplayCategorieLine($category, "100");
}

if ($page == "record_payment") {
?>
<td colspan=4><b><i><?= t("Catégories privées de ") ?><?= $activeUser->GetPartnerName() ?></i></b></td>
</tr>
<?php
$categories = $categoriesHandler->GetOutcomeCategoriesForUser($activeUser->GetPartnerId());
foreach ($categories as $category) {
	DisplayCategorieLine($category, "0");
}
$category = new Category();
$category->set("categoryId", 'USER/'.$activeUser->GetPartnerId());
$category->set("category", t("(Inconnue)"));
DisplayCategorieLine($category, "0");
}
?>
</table>