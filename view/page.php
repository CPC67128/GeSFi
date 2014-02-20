<?php
include '../security/security_manager.php';

$page = '';
if (isset($_GET['page']))
	$page = $_GET['page'];

$id = '';
if (isset($_GET['id']))
	$id = $_GET['id'];

$data = '';
if (isset($_GET['data']))
	$data = $_GET['data'];

$windowTitle = '';

$_SESSION['account_id'] = $id;
$_SESSION['page'] = $page;

function __autoload($class_name)
{
	$file = '../controller/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../model/'.$class_name . '.php';
	include $file;
}

$translator = new Translator();

$accountsManager = new AccountsManager();

$activeAccount = $accountsManager->GetCurrentActiveAccount();
$accountType = $activeAccount->get('type');

$pageName = $page;

if ($pageName == '-')
	$pageName = 'dashboard';

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

if ($accountType >= 1 && $accountType <= 10)
	$windowTitle .= $activeAccount->get('name');

switch ($pageName)
{
	case 'record_remark';
	case 'record_transfer';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'record_expense';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'income';
	case 'record_income';
		if ($accountType == 10)
			$pageName = 'income_investment';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'balance';
		if ($accountType == 1)
			$pageName = $pageName.'_private';
		else
			$pageName = $pageName.'_duo';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'records';
		if ($accountType == 10)
			$pageName = 'records_investment';
		include 'page_'.$pageName.'.php';
		break;

	case 'statistics';
		if ($accountType == -50 || $accountType == 0)
			include 'page_'.$pageName.'_global.php';
		else if ($accountType == 1)
			include 'page_'.$pageName.'_private.php';
		else if ($accountType == 10)
			include 'page_'.$pageName.'_investment.php';
		else if ($accountType == 100)
			include 'page_'.$pageName.'_investment_global.php';
		else
			include 'page_'.$pageName.'_duo.php';
		break;
	case 'investmentrecord_value':
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd('investmentrecord_value');
		break;

	case 'investmentrecord_income';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd('investmentrecord_income');
		break;
		
	case 'investmentrecord_debit';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd('investmentrecord_debit');
		break;
		
	case 'remark_investment';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd('remarkInvestment');
		break;

	case 'configuration';
		break;

	case 'investments_statistics';
	case 'configuration_accounts';
	case 'configuration_user';
	case 'configuration_category';
	case 'connection';
	case 'home';
	case 'dashboard';
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
		'../controller/controller.php?action=<?php echo $pageName; ?>',
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
?>
<script type="text/javascript">
SetTitle('<?php echo $windowTitle; ?>');
</script>
