<?php
include_once '../_sf_appzone_security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

$accountsManager = new AccountsManager();
$account = $accountsManager->GetCurrentActiveAccount();
?>
<?php if ($account->getType() != 0) { ?>
<img id='recordsMenuIcon' class='menuIcon' src="../media/recordsMenuIcon.jpg" />
<br />
Lignes
<br />
<br />
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
<?php } else { ?>
<img id='dashboardMenuIcon' class='menuIcon' src="../media/dashboardMenuIcon.png" />
<br />
Tableau de bord
<br />
<br />
<?php } ?>
<?php if ($account->getType() == 2 || $account->getType() == 3) { ?>
<img id='balanceMenuIcon' class='menuIcon' src="../media/balanceMenuIcon.png" />
<br />
Balance
<br />
<br />
<?php } ?>
<img id='statisticsMenuIcon' class='menuIcon' src="../media/statsMenuIcon.png" />
<br />
Statistiques
<br />
<br />
<?php if ($account->getType() == 0) { ?>
<img id='configurationMenuIcon' class='menuIcon' src="../media/configurationMenuIcon.png" />
<br />
Configuration
<br />
<br />
<img id='accountsMenuIcon' class='menuIcon' src="../media/accountsMenuIcon.png" />
<br />
Comptes
<br />
<br />
<?php } ?>
<a href="../appzone/copyright.htm">Copyright</a>
<br />
<a href="../appzone/index.php">AppZone</a>

<script type="text/javascript">
<?php if ($_SESSION['account_id'] != 'dashboard') { ?>
$("#recordsMenuIcon").click(function() {
	LoadRecords();
});

$("#expenseMenuIcon").click(function() {
	LoadPage('expense');
});

$("#incomeMenuIcon").click(function() {
	$.ajax({
        type : 'POST',
        url : 'page.php?name=income',
        dataType: 'html',
        success : function(data) {
            $('#content').html(data);
        }
    });
});

$("#transferMenuIcon").click(function() {
	$.ajax({
        type : 'POST',
        url : 'page.php?name=transfer',
        dataType: 'html',
        success : function(data) {
            $('#content').html(data);
        }
    });
});

$("#remarkMenuIcon").click(function() {
	$.ajax({
        type : 'POST',
        url : 'page.php?name=remark',
        dataType: 'html',
        success : function(data) {
            $('#content').html(data);
        }
    });
});
<?php } else { ?>
$("#dashboardMenuIcon").click(function() {
	$.ajax({
        type : 'POST',
        url : 'page.php?name=dashboard',
        dataType: 'html',
        success : function(data) {
            $('#content').html(data);
        }
    });
});
<?php } ?>

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

$("#configurationMenuIcon").click(function() {
	LoadConfigurationPage();
});

$("#accountsMenuIcon").click(function() {
	LoadPage('configuration_accounts');
});
</script>