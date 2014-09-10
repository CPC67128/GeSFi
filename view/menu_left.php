<?php
include 'menu.php';

$account = $accountsHandler->GetCurrentActiveAccount();

if ($area == 'administration')
{
	AddMenuLeftItem("administration_connection", "connectionMenuIcon.jpg", "Connections");
	AddMenuLeftItem("administration_accounts", "accountsMenuIcon.png", "Comptes");
	AddMenuLeftItem("administration_category", "categoriesMenuIcon.jpg", "Catégories");
	AddMenuLeftItem("administration_designation", "designationMenuIcon.png", "Désignation");
	AddMenuLeftItem("administration_user", "userMenuIcon.png", "Utilisateur");
}
else if ($area == 'investment' && $id == '')
{
	AddMenuLeftItem("investment_record_dashboard", "assetManagementMenuIcon.png", "Situation");
	AddMenuLeftItem("investment_record_statistics", "statsMenuIcon.png", "Graphiques");
}
else if ($area == 'investment')
{
	AddMenuLeftItem("records", "recordsMenuIcon.png", "Lignes");
	AddMenuLeftItem("investment_record_value", "valueMenuIcon.gif", "Valorisation");
	AddMenuLeftItem("investment_record_credit", "incomeMenuIcon.gif", "Enregistrement");
	AddMenuLeftItem("investment_record_debit", "expenseMenuIcon.png", "Dépense");
	AddMenuLeftItem("investment_record_remark", "remarkMenuIcon.png", "Remarque");
	AddMenuLeftItem("investment_record_statistics", "statsMenuIcon.png", "Statistiques");
}
else
{
	if ($id == '')
		AddMenuLeftItem("dashboard", "recordsMenuIcon.png", "Situation");
	else
		AddMenuLeftItem("records", "recordsMenuIcon.png", "Lignes");

	AddMenuLeftItem("record_expense", "expenseMenuIcon.png", "Dépense");
	AddMenuLeftItem("record_income", "incomeMenuIcon.gif", "Revenu");
	AddMenuLeftItem("record_transfer", "transferMenuIcon.png", "Virement");
	AddMenuLeftItem("record_remark", "remarkMenuIcon.png", "Remarque");
	AddMenuLeftItem("record_balance", "balanceMenuIcon.png", "Balance");
	AddMenuLeftItem("statistics", "statsMenuIcon.png", "Statistiques");
}


?>
<a href="../view/copyright.htm">Copyright</a>
<br />
<a href="../view/disconnection.php">Déconnection</a>

<script type="text/javascript">
$(".menuIcon").click(function() {
	ChangeContext_Page($(this).attr("id"));
});
</script>