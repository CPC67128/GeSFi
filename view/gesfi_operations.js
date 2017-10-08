//===== 
function DeleteRecord(recordIdToDelete)
{
	$.post (
			'../controller/controller.php?action=record_delete',
			{ recordId: recordIdToDelete },
			function(response) {
				LoadRecords_Normal()
			}
	);
}

function DeleteRecordInvestment(recordIdToDelete)
{
	$.post (
			'../controller/controller.php?action=investment_record_delete',
			{ recordId: recordIdToDelete },
			function(response, status) {
				LoadRecords_Normal();
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
				LoadAccountStatusInPageRecord();
			}
	);
}

function FlagRecord(recordIdToConfirm, sender, flag)
{
	if (sender.checked)
		confirmation = 1;
	else
		confirmation = 0;

	sender.disabled = true;
	$.post (
			'../controller/controller.php?action=record_flag',
			{ recordId: recordIdToConfirm , confirmed: confirmation , flag: flag },
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


function LoadAccountStatusInPageRecord()
{
	$.ajax({
		type : 'POST',
		url : 'page.php',
		data: {
			'page': 'record_inc_account_status',
			'area': '',
			'id': currentContext.id
		},
		dataType: 'html',
		success : function(data) {
			$('#accountStatus').html(data);
		}
	});
}

function GetDecimalValue(text) {
	var value = 0;
	value = parseFloat(text);
	return value;
}

function InterpretMinusFormula(text) {
	var value = 0;

	if (text.length == 0)
		return 0;

	var splits = text.split("-");
	if (splits.length > 0)
		value = GetDecimalValue(splits[0]);
	for (var i = 1; i < splits.length; i++)
		value -= GetDecimalValue(splits[i]);

	return value;
}

function InterpretPlusFormula(text) {
	var value = 0;

	if (text.length == 0)
		return 0;

	var splits = text.split("+");
	for (var i = 0; i < splits.length; i++)
		value += InterpretMinusFormula(splits[i]);
	return value;
}

function InterpretInlineFormula(text) {
	var value = 0;

	if (text.match("--" + "$") == "--") // this will be processed later
		return value;

	value = InterpretPlusFormula(text);
	return value;
}

function InterpretGlobalFormula(text, total) {
	var value = 0;

	if (text.match("--" + "$") == "--")
	{
		var splits = text.split("-");
		if (splits.length > 0)
			value = GetDecimalValue(splits[0]);
		value -= total;
	}

	return value;
}

function CalculateAllAmounts() {
	var value = 0;
	var total = 0;
	var text;

	for (var i=1;i<=60;i++) { // TODO 60 to replace with proper search
		if (document.getElementsByName('category'+i+'Formula').length > 0) {
			text = $("input[name='category"+i+"Formula']").val();
			text = text.replace(new RegExp(' ', 'g'), '');
			text = text.replace(new RegExp(',', 'g'), '.');
			value = InterpretInlineFormula(text);
			total += value;
	
			if (value != 0)
				$("input[name='category"+i+"Amount']").val( value );
			else
				$("input[name='category"+i+"Amount']").val('');
		}
	}

	for (i=1;i<=60;i++) { // TODO 60 to replace with proper search
		if (document.getElementsByName('category'+i+'Formula').length > 0) {
			text = $("input[name='category"+i+"Formula']").val();
			text = text.replace(new RegExp(' ', 'g'), '');
			text = text.replace(new RegExp(',', 'g'), '.');
			value = InterpretGlobalFormula(text, total);
			total += value;
	
			if (value != 0)
				$("input[name='category"+i+"Amount']").val( value );
		}
	}

	$("input[name='amount']").val(total);
}

/*
$("#designation").autocomplete({
	source: function( request, response ) {
		$.ajax({
			type: 'GET',
			url: "search_designation.php",
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			data: {
					'search_string': request.term,
					'type': 2
				},
			success: function( data ) {
				response( $.map( data.items, function( item ) {
					return {
						label: item
					}
				}));
			},

			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}

		});
	},
	minLength: 0,
	select: function( event, ui ) {
	}
});
*/