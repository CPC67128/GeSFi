//===== 
function DeleteRecord(recordIdToDelete)
{
	$.post (
			'../controller/controller.php?action=record_delete',
			{ recordId: recordIdToDelete },
			function(response, status) {
				LoadRecords();
			}
	);
}

function DeleteRecordInvestment(recordIdToDelete)
{
	$.post (
			'../controller/controller.php?action=investmentrecord_delete',
			{ recordId: recordIdToDelete },
			function(response, status) {
				LoadRecords();
			}
	);
}

function ConfirmRecord(recordIdToConfirm, sender)
{
	if (sender.checked)
		confirmation = 1;
	else
		confirmation = 0;

	sender.disabled = true;
	$.post (
			'../controller/controller.php?action=record_confirm',
			{ recordId: recordIdToConfirm , confirmed: confirmation },
			function(response, status) {
				sender.disabled = false;
			}
	);
}

function LoadRecords_All()
{
	$('#content').html('<img src="../media/loading.gif" />');
	ChangeContext_Page('record-fullview')
}

function LoadRecords_Normal()
{
	$('#content').html('<img src="../media/loading.gif" />');
	ChangeContext_Page('record')
}

function LoadRepaymentNeeds()
{
	$.ajax({
		type : 'POST',
		url : 'web_repayment_needs.php',
		dataType: 'html',
		success : function(data) {
			$('#repaymentNeeds').html(data);
		}
	});

	$.ajax({
		type : 'POST',
		url : 'web_repayment_needs_details.php',
		dataType: 'html',
		success : function(data) {
			$('#dialog-modal').html(data);
		}
	});

	$.ajax({
		type : 'POST',
		url : 'web_repayment_needs_conclusion.php',
		dataType: 'html',
		success : function(data) {
			$('#repaymentNeedsConclusion').html(data);
		}
	});
}

function LoadStatistics()
{
	$.ajax({
		type : 'POST',
		url : 'web_statistics.php',
		dataType: 'html',
		success : function(data) {
			$('#stats').html(data);
		}
	});

	$.ajax({
		type : 'POST',
		url : 'web_statistics_last_months.php',
		dataType: 'html',
		success : function(data) {
			$('#statsLastMonths').html(data);
		}
	});
}

function LoadCommonAccountStatistics()
{
	$.ajax({
		type : 'POST',
		url : 'web_statistics_common_account.php',
		dataType: 'html',
		success : function(data) {
			$('#commonAccount').html(data);
		}
	});

	$.ajax({
		type : 'POST',
		url : 'web_common_account_conclusion.php',
		dataType: 'html',
		success : function(data) {
			$('#commonAccountConclusion').html(data);
		}
	});
}

function CreateUnexpectedErrorWeb($error)
{
	var html = '<div class="ui-widget">';
	html += '<div class="ui-state-error ui-corner-all" style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em;">';
	html += '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
	html += '<strong><?php echo $LNG_Unexpected_error; ?></strong>' + $error + '</p>';
	html += '</div></div>';
	return html;
}

function ChangeAccount(id)
{
	if (id == 'dashboard')
	{
		$.post (
				'../controller/controller.php?action=dashboard',
				function(response, status) {
					LoadTopMenu();
					LoadPage('home');
				}
		);
	}
	else if (id == 'configuration')
	{
		$.post (
				'../controller/controller.php?action=configuration',
				function(response, status) {
					LoadTopMenu();
					LoadPage('configuration');
				}
		);
	}
	else
	{
		currentContext.id = id;
		currentContext.page = 'records';

		$.post (
				'../controller/controller.php?action=account_change',
				{ accountId: id },
				function(response, status) {
					LoadPage();
				}
		);
	}
}

function LogoutUser()
{
	$.post (
			'../controller/controller.php?action=user_logout',
			function(response, status) {
				window.location = 'page_login.php';
			}
	);
}
