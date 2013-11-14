<?php
include '../security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

$translator = new Translator();

$pageName = $_GET['name'];

$accountsManager = new AccountsManager();

$activeAccount = $accountsManager->GetCurrentActiveAccount();
$accountType = $activeAccount->getType();

if ($accountType == -50 && $pageName == 'records')
	$pageName = 'home';

if ($accountType == -100 && $pageName == 'records')
	$pageName = '';

if ($accountType == 100 && $pageName == 'records')
	$pageName = 'asset_management';

$categoryHandler = new CategoryHandler();

$recordsManager = new RecordsManager();

$usersHandler = new UsersHandler();
$activeUser = $usersHandler->GetCurrentUser();

switch ($pageName)
{
	case 'remark';
	case 'transfer';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'expense';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'income';
		if ($activeAccount->getType() == 10)
			$pageName = 'income_investment';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'balance';
		if ($activeAccount->getType() == 1)
			$pageName = $pageName.'_private';
		else
			$pageName = $pageName.'_duo';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'records';
		if ($activeAccount->getType() == 10)
			$pageName = 'records_investment';
		include 'page_'.$pageName.'.php';
		break;

	case 'statistics';
		if ($activeAccount->getType() == -50 || $activeAccount->getType() == 0)
			include 'page_'.$pageName.'_global.php';
		else if ($activeAccount->getType() == 1)
			include 'page_'.$pageName.'_private.php';
		else if ($activeAccount->getType() == 10)
			include 'page_'.$pageName.'_investment.php';
		else if ($activeAccount->getType() == 100)
			include 'page_'.$pageName.'_investment_global.php';
		else
			include 'page_'.$pageName.'_duo.php';
		break;

	case 'value_investment':
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd('valueInvestment');
		break;

	case 'income_investment';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd('incomeInvestment');
		break;
		
	case 'debit_investment';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd('debit_investment');
		break;
		
	case 'remark_investment';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd('remarkInvestment');
		break;

	case 'investments_statistics';
	case 'configuration';
	case 'configuration_accounts';
	case 'configuration_user';
	case 'configuration_category';
	case 'connection';
	case 'home';
	case 'investment';
	case 'asset_management';
		include 'page_'.$pageName.'.php';
		break;
}

// ------------------------------------------------------------------------------------------------

function AddFormManagementEnd($pageName)
{
?>
<script type='text/javascript'>
$("#form").submit( function () {
	document.getElementById("submitForm").disabled = true;
	$.post (
		'controller.php?action=<?php echo $pageName; ?>',
		$(this).serialize(),
		function(response, status) {
			$("#formResult").stop().show();
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					$("#formResult").html(response);
				}
				else {
					LoadRecords();
				}
			}
			else {
				$("#formResult").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitForm").disabled = false;

			setTimeout(function() {
				$("#formResult").fadeOut("slow", function () {
					$('#formResult').empty();
				})
			}, 4000);
		}
	);
	return false;
});

$( "#datePicker" ).datepicker({
	showOn: "both",
	buttonImage: "../media/calendar.gif",
	buttonImageOnly: true,
	dateFormat: "yy-mm-dd",
	firstDay: 1,
	dayNamesShort: [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ],
	dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
	dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
	monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ]
});

$( ".datePicker" ).datepicker({
	showOn: "both",
	buttonImage: "../media/calendar.gif",
	buttonImageOnly: true,
	dateFormat: "yy-mm-dd",
	firstDay: 1,
	dayNamesShort: [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ],
	dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
	dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
	monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ]
});
</script>
<?php
}
