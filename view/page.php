<?php
include '../_sf_appzone_security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

$translator = new Translator();

$accountsManager = new AccountsManager();
$activeAccount = $accountsManager->GetCurrentActiveAccount();

$accountType = $activeAccount->getType();

$categoryHandler = new CategoryHandler();

$recordsManager = new RecordsManager();

$pageName = $_GET['name'];

switch ($pageName)
{
	case 'remark';
	case 'transfer';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'expense';
		if ($activeAccount->getType() == 1)
		{
			$pageName = $pageName.'_private';
			include 'page_'.$pageName.'.php';
		}
		else if ($activeAccount->getType() == 3)
		{
			include 'page_'.$pageName.'_duo.php';
			$pageName = $pageName.'_duo_account';
		}
		else
		{
			include 'page_'.$pageName.'_duo.php';
			$pageName = $pageName.'_duo_virtual_account';
		}
		AddFormManagementEnd($pageName);
		break;

	case 'income';
	case 'balance';
		if ($activeAccount->getType() == 1)
			$pageName = $pageName.'_private';
		else
			$pageName = $pageName.'_duo';
		include 'page_'.$pageName.'.php';
		AddFormManagementEnd($pageName);
		break;

	case 'records';
	/*
		if ($activeAccount->getType() == 1)
			$pageName = 'page_'.$pageName.'_single_account.php';
		else if ($activeAccount->getType() == 3)
			$pageName = 'page_'.$pageName.'_duo_account.php';
		else
			$pageName = 'page_'.$pageName.'_duo_virtual_account.php';
		*/
		include 'page_'.$pageName.'.php';;
		break;

	case 'statistics';
		if ($activeAccount->getType() == 1)
			include 'page_'.$pageName.'_private.php';
		else
			include 'page_'.$pageName.'_duo.php';
		break;

	case 'configuration';
	case 'none';
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
</script>
<?php
}
