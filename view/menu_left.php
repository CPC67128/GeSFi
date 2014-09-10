<?php
include 'menu.php';

$account = $accountsHandler->GetCurrentActiveAccount();

if ($area == 'administration')
{
	AddMenuLeftItem("administration_connection", "connectionMenuIcon.jpg", $translator->getTranslation("Connections"));
	AddMenuLeftItem("administration_accounts", "accountsMenuIcon.png", $translator->getTranslation("Comptes"));
	AddMenuLeftItem("administration_category", "categoriesMenuIcon.jpg", $translator->getTranslation("Catégories"));
	AddMenuLeftItem("administration_designation", "designationMenuIcon.png", $translator->getTranslation("Désignation"));
	AddMenuLeftItem("administration_user", "userMenuIcon.png", $translator->getTranslation("Utilisateur"));
}
else if ($area == 'investment' && $id == '')
{
	AddMenuLeftItem("investment_record_dashboard", "assetManagementMenuIcon.png", $translator->getTranslation("Situation"));
	AddMenuLeftItem("investment_record_statistics", "statsMenuIcon.png", $translator->getTranslation("Graphiques"));
}
else if ($area == 'investment')
{
	AddMenuLeftItem("investment_record", "recordsMenuIcon.png", $translator->getTranslation("Lignes"));
	AddMenuLeftItem("investment_record_value", "valueMenuIcon.gif", $translator->getTranslation("Valorisation"));
	AddMenuLeftItem("investment_record_credit", "incomeMenuIcon.gif", $translator->getTranslation("Enregistrement"));
	AddMenuLeftItem("investment_record_debit", "expenseMenuIcon.png", $translator->getTranslation("Dépense"));
	AddMenuLeftItem("investment_record_remark", "remarkMenuIcon.png", $translator->getTranslation("Remarque"));
	AddMenuLeftItem("investment_record_statistics", "statsMenuIcon.png", $translator->getTranslation("Statistiques"));
}
else
{
	if ($id == '')
		AddMenuLeftItem("record", "recordsMenuIcon.png", $translator->getTranslation("Situation"));
	else
		AddMenuLeftItem("record", "recordsMenuIcon.png", $translator->getTranslation("Lignes"));

	AddMenuLeftItem("record_expense", "expenseMenuIcon.png", $translator->getTranslation("Dépense"));
	AddMenuLeftItem("record_income", "incomeMenuIcon.gif", $translator->getTranslation("Revenu"));
	AddMenuLeftItem("record_transfer", "transferMenuIcon.png", $translator->getTranslation("Virement"));
	AddMenuLeftItem("record_remark", "remarkMenuIcon.png", $translator->getTranslation("Remarque"));
	AddMenuLeftItem("record_balance", "balanceMenuIcon.png", $translator->getTranslation("Balance"));
	AddMenuLeftItem("statistics", "statsMenuIcon.png", $translator->getTranslation("Statistiques"));
}
?>
<a href="../view/copyright.htm">Copyright</a>
<br />
<a href="#" onClick="LogoutUser(); return false;">Déconnection</a>

<script type="text/javascript">
$(".menuIcon").click(function() {
	ChangeContext_Page($(this).attr("id"));
});
</script>