$.fx.speeds._default = 200;

$(function() {
	LoadTopMenu();
	LoadLeftMenu();

	LoadRecords();
})

function DeleteRecord(recordIdToDelete)
{
	$.post (
			'controller.php?action=deleteRecord',
			{ recordId: recordIdToDelete },
			function(response, status) {
				LoadRecords();
			}
		);
}

function LoadPage(pageName)
{
	$.ajax({
        type : 'POST',
        url : 'page.php?name='+pageName,
        dataType: 'html',
        success : function(data) {
            $('#content').html(data);
            LoadLeftMenu();
        }
    });
}


function LoadConfigurationPage()
{
	$.ajax({
        type : 'POST',
        url : 'page_configuration.php',
        dataType: 'html',
        success : function(data) {
            $('#content').html(data);
        }
    });
}

function LoadRecords()
{
	$('#content').html('<img src="../media/loading.gif" />');
	LoadPage('records');
}

function LoadTopMenu()
{
	$.ajax({
        type : 'POST',
        url : 'content_top_menu.php',
        dataType: 'html',
        success : function(data) {
            $('#topMenu').html(data);
        }
    });
}

function LoadLeftMenu()
{
	$.ajax({
        type : 'POST',
        url : 'content_left_menu.php',
        dataType: 'html',
        success : function(data) {
            $('#leftMenu').html(data);
        }
    });
}

function LoadAllRecords()
{
	$('#content').html('<img src="../media/loading.gif" />');
	LoadPage('records&fullview');
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
				'controller.php?action=dashboard',
				function(response, status) {
					LoadTopMenu();
					LoadPage('dashboard');
				}
		);
	}
	else
	{
		$.post (
				'controller.php?action=changeAccount',
				{ accountId: id },
				function(response, status) {
					LoadTopMenu();
					LoadRecords();
				}
		);
	}
}

