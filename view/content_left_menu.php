<?php
include_once '../security/security_manager.php';

function __autoload($class_name)
{
	$file = '../controller/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../model/'.$class_name . '.php';
	include $file;
}


$accountsManager = new AccountsManager();
$account = $accountsManager->GetCurrentActiveAccount();
?>

<?php if ($account->get('type') == -50) { ?>
<img id='dashboardMenuIcon' class='menuIcon' src="../media/homeMenuIcon.png" />
<br />
Accueil
<br />
<br />
<?php } ?>

<?php if ($account->get('type') == 100) { ?>
<img id='recordsMenuIcon' class='menuIcon' src="../media/assetManagementMenuIcon.png" />
<br />
Situation
<br />
<br />
<img id='investmentsStatisticsMenuIcon' class='menuIcon' src="../media/statsMenuIcon.png" />
<br />
Statistiques
<br />
<br />
<?php
}
?>

<?php if ($account->get('type') == 10) { ?>
<img id='recordsMenuIcon' class='menuIcon' src="../media/recordsMenuIcon.png" />
<br />
Lignes
<br />
<br />
<img id='investmentValueMenuIcon' class='menuIcon' src="../media/valueMenuIcon.gif" />
<br />
Valorisation
<br />
<br />
<img id='investmentIncomeMenuIcon' class='menuIcon' src="../media/incomeMenuIcon.gif" />
<br />
Enregistrement
<br />
<br />
<img id='investmentDebitMenuIcon' class='menuIcon' src="../media/expenseMenuIcon.png" />
<br />
Dépense
<br />
<br />
<img id='remarkInvestmentMenuIcon' class='menuIcon' src="../media/remarkMenuIcon.png" />
<br />
Remarque
<br />
<br />
<img id='statisticsMenuIcon' class='menuIcon' src="../media/statsMenuIcon.png" />
<br />
Statistiques
<br />
<br />
<?php } ?>

<?php if ($account->get('type') == 0) { ?>
<img id='dashboardMenuIcon' class='menuIcon' src="../media/recordsMenuIcon.png" /><br />Situation<br /><br />
<?php } ?>

<?php if ($account->get('type') > 0 && $account->get('type') < 10) { ?>
<img id='recordsMenuIcon' class='menuIcon' src="../media/recordsMenuIcon.png" /><br />Lignes<br /><br />
<?php } ?>

<?php if ($account->get('type') >= 0 && $account->get('type') < 10) { ?>
<img id='expenseMenuIcon' class='menuIcon' src="../media/expenseMenuIcon.png" />
<br />
Dépense
<br />
<br />
<img id='incomeMenuIcon' class='menuIcon' src="../media/incomeMenuIcon.gif" />
<br />
Revenu
<br />
<br />
<img id='transferMenuIcon' class='menuIcon' src="../media/transferMenuIcon.png" />
<br />
Virement
<br />
<br />
<img id='remarkMenuIcon' class='menuIcon' src="../media/remarkMenuIcon.png" />
<br />
Remarque
<br />
<br />
<?php } ?>

<?php if ($account->get('type') == 0 || $account->get('type') == 2 || $account->get('type') == 3 || $account->get('type') == -50) { ?>
<img id='balanceMenuIcon' class='menuIcon' src="../media/balanceMenuIcon.png" />
<br />
Balance
<br />
<br />
<img id='statisticsMenuIcon' class='menuIcon' src="../media/statsMenuIcon.png" />
<br />
Statistiques
<br />
<br />
<?php } ?>

<?php if ($account->get('type') == -100) { ?>
<img id='connectionMenuIcon' class='menuIcon' src="../media/connectionMenuIcon.jpg" />
<br />
Connections
<br />
<br />
<img id='accountsMenuIcon' class='menuIcon' src="../media/accountsMenuIcon.png" />
<br />
Comptes
<br />
<br />
<img id='categoriesMenuIcon' class='menuIcon' src="../media/categoriesMenuIcon.jpg" />
<br />
Catégories
<br />
<br />
<img id='userMenuIcon' class='menuIcon' src="../media/userMenuIcon.png" />
<br />
Utilisateur
<br />
<br />
<?php } ?>
<a href="../view/copyright.htm">Copyright</a>
<br />
<a href="../view/disconnection.php">Déconnection</a>

<script type="text/javascript">

$("#dashboardMenuIcon").click(function() {
	ChangeContext_Page('dashboard');
});

$("#recordsMenuIcon").click(function() {
	ChangeContext_Page('records');
});

$("#expenseMenuIcon").click(function() {
	ChangeContext_Page('record_expense');
});

$("#incomeMenuIcon").click(function() {
	ChangeContext_Page('record_income');
});

$("#transferMenuIcon").click(function() {
	ChangeContext_Page('record_transfer');
});

$("#remarkMenuIcon").click(function() {
	ChangeContext_Page('record_remark');
});

$("#connectionMenuIcon").click(function() {
	$.ajax({
        type : 'POST',
        url : 'page.php?name=connection',
        dataType: 'html',
        success : function(data) {
            $('#content').html(data);
        }
    });
});

$("#balanceMenuIcon").click(function() {
	$('#content').html('<img src="../media/loading.gif" />');
	$.ajax({
        type : 'POST',
        url : 'page.php?name=balance',
        dataType: 'html',
        success : function(data) {
            $('#content').html(data);
        }
    });
});

$("#statisticsMenuIcon").click(function() {
	$('#content').html('<img src="../media/loading.gif" />');
	LoadPage('statistics');
});

$("#remarkInvestmentMenuIcon").click(function() {
	$('#content').html('<img src="../media/loading.gif" />');
	LoadPage('remark_investment');
});

$("#investmentsStatisticsMenuIcon").click(function() {
	$('#content').html('<img src="../media/loading.gif" />');
	ChangeContext_Page('statistics');
});

$("#investmentValueMenuIcon").click(function() {
	ChangeContext_Page('investmentrecord_value');
});


$("#investmentIncomeMenuIcon").click(function() {
	ChangeContext_Page('investmentrecord_income');
});

$("#investmentDebitMenuIcon").click(function() {
	ChangeContext_Page('investmentrecord_debit');
});


$("#configurationMenuIcon").click(function() {
	LoadConfigurationPage();
});

$("#accountsMenuIcon").click(function() {
	ChangeContext_Page('configuration_accounts');
});

$("#userMenuIcon").click(function() {
	ChangeContext_Page('configuration_user');
});

$("#categoriesMenuIcon").click(function() {
	ChangeContext_Page('configuration_category');
});
</script>