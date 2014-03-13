<?php
include_once '../security/security_manager.php';

$accountsManager = new AccountsManager();
$account = $accountsManager->GetCurrentActiveAccount();

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

	AddMenuEntry("record_expense", "expenseMenuIcon.png", "Dépense");
	AddMenuEntry("record_income", "incomeMenuIcon.gif", "Revenu");
	AddMenuEntry("record_transfer", "transferMenuIcon.png", "Virement");
	AddMenuEntry("record_remark", "remarkMenuIcon.png", "Remarque");
	AddMenuEntry("balance", "balanceMenuIcon.png", "Balance");
	AddMenuEntry("statistics", "statsMenuIcon.png", "Statistiques");
}

if ($account->get('type') == 100)
{
	AddMenuEntry("records", "assetManagementMenuIcon.png", "Situation");
	AddMenuEntry("statistics", "statsMenuIcon.png", "Graphiques");
}

if ($account->get('type') >= 10 && $account->get('type') <= 12)
{
	AddMenuEntry("records", "recordsMenuIcon.png", "Lignes");
	AddMenuEntry("investmentrecord_value", "valueMenuIcon.gif", "Valorisation");
	AddMenuEntry("investmentrecord_income", "incomeMenuIcon.gif", "Enregistrement");
	AddMenuEntry("investmentrecord_debit", "expenseMenuIcon.png", "Dépense");
	AddMenuEntry("investmentrecord_remark", "remarkMenuIcon.png", "Remarque");
	AddMenuEntry("statistics", "statsMenuIcon.png", "Statistiques");
}

if ($account->get('type') == -100)
{
	AddMenuEntry("connection", "connectionMenuIcon.jpg", "Connections");
	AddMenuEntry("configuration_accounts", "accountsMenuIcon.png", "Comptes");
	AddMenuEntry("configuration_category", "categoriesMenuIcon.jpg", "Catégories");
	AddMenuEntry("configuration_user", "userMenuIcon.png", "Utilisateur");
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