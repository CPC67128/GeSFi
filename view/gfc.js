$.fx.speeds._default = 200;

var PAGE_UNDEFINED = 'dashboard';
var ID_UNDEFINED = '';
var DATA_UNDEFINED = '';

function Context(page, id, data) {
	this.page = page;
	this.id = id;
	this.data = data;
}

var currentContext = new Context(PAGE_UNDEFINED, ID_UNDEFINED, DATA_UNDEFINED);

function ManageHash() {
	var hash = document.location.hash.replace("#", "");
	var hashSplit = hash.split("/");

	if (hashSplit.length == 3) {
		currentContext.page = hashSplit[0];
		currentContext.id = hashSplit[1];
		currentContext.data = hashSplit[2];
		return true;
	}

	return false;
}

if (!ManageHash()) {
	UpdateUrl();
}

function UpdateUrl() {
	var hash = currentContext.page;
	hash += "/" + currentContext.id;
	hash += "/" + currentContext.data;

	document.location.hash = hash;

	setFavicon(); // Bug firefox: favicon disappears http://kilianvalkhof.com/2010/javascript/the-case-of-the-disappearing-favicon/
}

function setFavicon() {
	  var link = $('link[type="image/ico"]').remove().attr("href");
	  $('<link href="' + link + '" rel="shortcut icon" type="image/ico" />').appendTo('head');
}

function SetTitle(title) {
	var currentTitle = 'BudgetFox';
	if (title != '') {
		currentTitle = "BudgetFox - " + title;
	}

	document.title = currentTitle;
}

/*** Executed at page refresh ***/
$(function() {
	//DEBUG alert('function()');
	LoadPage();
})

/*** Action on hash change  ***/
$(window).bind('hashchange', function() {
	if (ManageHash()) {
		//DEBUG alert('HashChange event : page=' + currentContext.page + ", id=" + currentContext.id + ", data=" + currentContext.data);

		LoadPage();
	}
});

/*** Change context of the application ***/
function ChangeContext(page, id, data) {
	//DEBUG alert('ChangeContext(' + page + ', ' + id + ', ' + data + ')');

	currentContext.page = page;
	currentContext.id = id;
	currentContext.data = data;

	UpdateUrl();
}

function ChangeContext_Page(page) {
	//DEBUG alert('ChangeContext_Page(' + page + ')');

	currentContext.page = page;

	UpdateUrl();
}

/*** Load page according to the current context ***/
function LoadPage() {
	//DEBUG alert('LoadPage() : ' + 'page=' + currentContext.page + ', id=' + currentContext.id + ', data=' + currentContext.data);

	$('#content').html('<img src="../media/loading.gif" />');
	$.ajax({
        type : 'POST',
        url : 'page.php?page=' + currentContext.page + '&id=' + currentContext.id + '&data=' + currentContext.data,
        dataType: 'html',
        success : function(data) {
            LoadTopMenu();
            LoadLeftMenu();
            $('#content').html(data);
        }
    });
}

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

/*
function LoadPage(pageName)
{
	return;
	
	alert('LoadPage(' + pageName + ')');
	
	$.ajax({
        type : 'POST',
        url : 'page.php?name=' + pageName,
        dataType: 'html',
        success : function(data) {
            $('#content').html(data);
            LoadLeftMenu();

            SetContext(pageName);
        }
    });
}
*/

function SetContext(pageName) {
	currentContext.page = pageName;
	UpdateUrl();	
}

function LoadConfigurationPage()
{
	LoadPage('configuration');
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

	$.ajax({
        type : 'POST',
        url : 'content_top_second_line_menu.php',
        dataType: 'html',
        success : function(data) {
            $('#topSecondLineMenu').html(data);
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

		$.post (
				'../controller/controller.php?action=account_change',
				{ accountId: id },
				function(response, status) {
					LoadTopMenu();
					LoadRecords();
				}
		);
	}
}

