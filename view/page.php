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
$_SESSION['data'] = $data;

$translator = new Translator();

$accountsHandler = new AccountsHandler();

//if ($id != '')
//	$activeAccount = $accountsManager->GetAccount($id);
//else
$activeAccount = $accountsHandler->GetCurrentActiveAccount();
$accountType = $activeAccount->get('type');

$pageName = $page;

$fullRecordsView = false;

if ($pageName == 'records-fullview')
{
	$pageName = 'records';
	echo 'FULLVIEW';
	$fullRecordsView = true;
	
}

if ($pageName == '-')
	$pageName = 'dashboard';

if ($accountType == -50 && $pageName == 'records')
	$pageName = 'home';

if ($accountType == -100 && $pageName == 'records')
	$pageName = '';

//if ($accountType == 100 && $pageName == 'records')
	//$pageName = 'investment_records_dashboard';

$categoriesHandler = new CategoriesHandler();

$recordsHandler = new RecordsHandler();

$usersHandler = new UsersHandler();
$activeUser = $usersHandler->GetCurrentUser();
$partnerUser = $usersHandler->GetUser($activeUser->GetPartnerId());

if ($accountType >= 1 && $accountType <= 10)
	$windowTitle .= $activeAccount->get('name');

switch ($pageName)
{
	case 'records_remark';
	case 'records_transfer';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'records_expense';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'income';
	case 'records_income';
		if ($accountType >= 10 && $accountType <= 19)
			$pageName = 'income_investment';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'records_balance';
		include 'page_'.$pageName.'.php';
		break;

	case 'dashboard';
		include 'page_records.php';
		break;

	case 'records';
		if ($accountType >= 10 && $accountType <= 19)
			$pageName = 'investment_records';
		include 'page_'.$pageName.'.php';
		break;

	case 'investment_records_statistics';
		if ($accountType == -50 || $accountType == 0)
			include 'page_'.$pageName.'_global.php';
		else if ($accountType == 1)
			include 'page_'.$pageName.'_private.php';
		else if ($accountType == 10)
			include 'page_'.$pageName.'.php';
		else if ($accountType == 100)
			include 'page_'.$pageName.'_global.php';
		else
			include 'page_'.$pageName.'_duo.php';
		break;

	case 'statistics';
		if ($accountType == -50 || $accountType == 0)
			include 'page_'.$pageName.'_global.php';
		else if ($accountType == 2 || $accountType == 3)
			include 'page_'.$pageName.'_duo.php';
		else if ($accountType == 10)
			include 'page_'.$pageName.'_investment.php';
		else if ($accountType == 100)
			include 'page_'.$pageName.'_investment_global.php';
		else
			include 'page_'.$pageName.'_private.php';
		break;

	case 'investment_records_value':
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd('investment_records_value');
		break;

	case 'investment_records_credit';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd('investment_records_income');
		break;
		
	case 'investment_records_debit';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd('investment_records_debit');
		break;
		
	case 'investment_records_remark';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd('investment_records_remark');
		break;

	case 'configuration';
		break;

	case 'administration_accounts';
	case 'administration_user';
	case 'administration_category';
	case 'administration_connection';
	case 'home';
	case 'investment';
	case 'investment_records_dashboard';
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
					ChangeContext_Page('records');
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
