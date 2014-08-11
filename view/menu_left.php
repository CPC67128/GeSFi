<?php
include_once '../security/security_manager.php';

$accountsHandler = new AccountsHandler();
$account = $accountsHandler->GetCurrentActiveAccount();

function AddMenuEntry($id, $image, $text)
{
	echo '<img id="'.$id.'" class="menuIcon" src="../media/'.$image.'" /><br />'.$text.'<br /><br />';
}

if ($account->get('type') >= 0 && $account->get('type') < 10)
{
	if ($account->get('type') == 0)
		AddMenuEntry("dashboard", "recordsMenuIcon.png", "Situation");
	else
		AddMenuEntry("records", "recordsMenuIcon.png", "Lignes");

	AddMenuEntry("records_expense", "expenseMenuIcon.png", "Dépense");
	AddMenuEntry("records_income", "incomeMenuIcon.gif", "Revenu");
	AddMenuEntry("records_transfer", "transferMenuIcon.png", "Virement");
	AddMenuEntry("records_remark", "remarkMenuIcon.png", "Remarque");
	AddMenuEntry("records_balance", "balanceMenuIcon.png", "Balance");
	AddMenuEntry("statistics", "statsMenuIcon.png", "Statistiques");
}

if ($account->get('type') == 100)
{
	AddMenuEntry("investment_records_dashboard", "assetManagementMenuIcon.png", "Situation");
	AddMenuEntry("investment_records_statistics", "statsMenuIcon.png", "Graphiques");
}

if ($account->get('type') >= 10 && $account->get('type') <= 12)
{
	AddMenuEntry("records", "recordsMenuIcon.png", "Lignes");
	AddMenuEntry("investment_records_value", "valueMenuIcon.gif", "Valorisation");
	AddMenuEntry("investment_records_credit", "incomeMenuIcon.gif", "Enregistrement");
	AddMenuEntry("investment_records_debit", "expenseMenuIcon.png", "Dépense");
	AddMenuEntry("investment_records_remark", "remarkMenuIcon.png", "Remarque");
	AddMenuEntry("investment_records_statistics", "statsMenuIcon.png", "Statistiques");
}

if ($account->get('type') == -100)
{
	AddMenuEntry("administration_connection", "connectionMenuIcon.jpg", "Connections");
	AddMenuEntry("administration_accounts", "accountsMenuIcon.png", "Comptes");
	AddMenuEntry("administration_category", "categoriesMenuIcon.jpg", "Catégories");
	AddMenuEntry("administration_user", "userMenuIcon.png", "Utilisateur");
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