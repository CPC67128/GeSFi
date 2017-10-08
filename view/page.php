<?php
include '../component/component_autoload.php';
include '../component/component_security.php';

$translator = new Translator();
$t = $translator;

function t($text)
{
	global $t;
	return $t->t($text);
}

$specialPage = '';
if (isset($_GET['special_page']))
	$specialPage = $_GET['special_page'];

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

if (!isset($_POST))
	exit();

$page = isset($_POST['page']) ? $_POST['page'] : '';
$area = isset($_POST['area']) ? $_POST['area'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$data = isset($_POST['data']) ? $_POST['data'] : '';

$translator = new Translator();

$accountsHandler = new AccountsHandler();

if ($id != '')
	$activeAccount = $accountsHandler->GetAccount($id);
else
	$activeAccount = $accountsHandler->GetCurrentActiveAccount();
$accountType = $activeAccount->get('type');

$pageName = $page;

$fullRecordsView = false;

if ($pageName == 'record-fullview')
{
	$pageName = 'record';
	$fullRecordsView = true;
	
}

if ($pageName == '-')
	$pageName = 'records';

if ($accountType == -50 && $pageName == 'records')
	$pageName = 'home';

if ($accountType == -100 && $pageName == 'records')
	$pageName = '';

//if ($accountType == 100 && $pageName == 'records')
	//$pageName = 'investment_records_dashboard';

$categoriesHandler = new CategoriesHandler();

$recordsHandler = new RecordsHandler();
$statisticsHandler = new StatisticsHandler();

$usersHandler = new UsersHandler();
$activeUser = $usersHandler->GetCurrentUser();
$partnerUser = $usersHandler->GetUser($activeUser->GetPartnerId());

if ($accountType >= 1 && $accountType <= 10)
	$windowTitle .= $activeAccount->get('name');

// --------------------------------------------------------------------------------------------------

if ($pageName == 'record' && $area == 'investment')
	$pageName = 'investment_record';

// Default page for administration
if ($pageName == 'administration' && $area == 'administration' && $id == '')
	$pageName = 'administration_connection';

// Special statistics page
if (!empty($specialPage))
	$pageName = $specialPage;

switch ($pageName)
{
	case 'record_remark':
	case 'record_transfer':
	case 'record_payment':
	case 'record_income':
	case 'investment_record_value':
	case 'investment_record_credit';
	case 'investment_record_withdrawal';
	case 'investment_record_income';
	case 'investment_record_remark';
		include 'pages/page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;
/*
	case 'investment_record_statistics';
		if ($accountType == -50 || $accountType == 0)
			include 'pages/page_'.$pageName.'_global.php';
		else if ($accountType == 1)
			include 'pages/page_'.$pageName.'_private.php';
		else if ($accountType == 10)
			include 'pages/page_'.$pageName.'.php';
		else if ($accountType == 100)
			include 'pages/page_'.$pageName.'_global.php';
		else
			include 'pages/page_'.$pageName.'_duo.php';
		break;

	case 'statistics';
		if ($accountType == -50 || $accountType == 0)
			include 'pages/page_'.$pageName.'_global.php';
		else if ($accountType == 2 || $accountType == 3)
			include 'pages/page_'.$pageName.'_duo.php';
		else if ($accountType == 10)
			include 'pages/page_'.$pageName.'_investment.php';
		else if ($accountType == 100)
			include 'pages/page_'.$pageName.'_investment_global.php';
		else
			include 'pages/page_'.$pageName.'_private.php';
		break;
*/

	case 'configuration';
		break;

	default:
		include 'pages/page_'.$pageName.'.php';
		break;
}

// ------------------------------------------------------------------------------------------------

function AddFormButton()
{
	?>
	<div class="formButtons" style="padding-top: 15px;">
	<input value="<?= t('Ajouter') ?>" id="submitForm" type="submit">
	<input id="resetForm" name="reset" value="<?= t('Effacer') ?>" type="reset">
	<div id="formResult"></div>
	</div>
	<?php
}

function AddReccurenceSubForm()
{
	?>
	<?= t('Périodicité:') ?><br>
	<input type="radio" name="periodicity" value="unique" checked><?= t('unique') ?></input><br>
	<input type="radio" name="periodicity" value="monthly"><?= t('tous les mois') ?></input><br>
	<?= t('pendant') ?> <input type="text" name="periodicityNumber" size="3"> <?= t('mois') ?>
	<?php
}

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
					ChangeContext_Page('record');
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

$( "#datePickerInline" ).datepicker({
	dateFormat: "yy-mm-dd",
	firstDay: 1,
	dayNamesShort: [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ],
	dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
	dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
	monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ],
	altField : '#datePickerHidden'
});
$( "#datePickerInline2" ).datepicker({
	dateFormat: "yy-mm-dd",
	firstDay: 1,
	dayNamesShort: [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ],
	dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
	dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
	monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ],
	altField : '#datePickerHidden2'
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

function DisplayCategorieLine($category, $chargeLevel="50", $isChargeLevelFieldHidden=false)
{
	global $i;

	$categoryId = $category->get('categoryId');
	$category = $category->get('category');

	?>
	<tr>
	<td><?= $category ?></td>
	<td><input type="text" name="category<?= $i ?>Formula" tabindex="<?= ($i * 2) ?>" size="12" onkeyup="javascript: CalculateAllAmounts();">&nbsp;=&nbsp;</td>
	<td><input type="text" name="category<?= $i ?>Amount"  tabindex="-1" size="6" readonly> &euro;<input type="hidden" name="category<?= $i ?>CategoryId" tabindex="-1" size="6" readonly value='<?= $categoryId ?>'></td>
	<td align="center"><input type="<?= $isChargeLevelFieldHidden ? 'hidden' : 'text' ?>" name="category<?= $i ?>ChargeLevel" tabindex="<?= (($i * 2) + 1) ?>" value="<?= $chargeLevel ?>" size="2"><?= $isChargeLevelFieldHidden ? '' : ' %' ?></td>
	</tr>
	<?php
	$i++;
}

?>
<script type="text/javascript">
SetTitle('<?php echo $windowTitle; ?>');
</script>
