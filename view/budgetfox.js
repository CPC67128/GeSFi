$.fx.speeds._default = 200;

var PAGE_UNDEFINED = 'record';
var AREA_UNDEFINED = '';
var ID_UNDEFINED = '';
var DATA_UNDEFINED = '';

// ========== Context

function Context(page, area, id, data) {
	this.page = page;
	this.area = area;
	this.id = id;
	this.data = data;
}

var currentContext = new Context(PAGE_UNDEFINED, AREA_UNDEFINED, ID_UNDEFINED, DATA_UNDEFINED);

// ========== URL Handling

function ManageHash() {
	// alert('ManageHash()');
	var hash = document.location.hash.replace("#", "");
	// alert('hash=' + hash);
	var hashSplit = hash.split("/");
	// alert('hash length=' + hashSplit.length);

	if (hashSplit.length == 5) {
		currentContext.page = hashSplit[0];
		currentContext.area = hashSplit[1];
		currentContext.id = hashSplit[2];
		currentContext.data = hashSplit[3];
		return true;
	}

	return false;
}

function UpdateUrl() {
	var now = new Date();

	var hash = currentContext.page;
	hash += "/" + currentContext.area;
	hash += "/" + currentContext.id;
	hash += "/" + currentContext.data;
	hash += "/" + now.toISOString();

	// alert(hash);
	document.location.hash = hash;

	setFavicon(); // Bug firefox: favicon disappears http://kilianvalkhof.com/2010/javascript/the-case-of-the-disappearing-favicon/
}

if (!ManageHash()) {
	UpdateUrl();
}

// ========== Général UI information handling

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

// ========== Page loading

// Executed at page refresh
$(function() {
	// alert('function() raised');
	LoadPage();
})

// Action on hash change
$(window).bind('hashchange', function() {
	// alert('$(window).bind(hashchange, function()');
	if (ManageHash()) {
		// alert('HashChange event : page=' + currentContext.page + ', area=' + currentContext.area + ', id=' + currentContext.id + ', data=' + currentContext.data);

		LoadPage();
	}
});

// Change context of the application
function ChangeContext(page, area, id, data) {
	// alert('ChangeContext(' + page + ', ' + area + ', ' + id + ', ' + data + ')');

	currentContext.page = page;
	currentContext.area = area;
	currentContext.id = id;
	currentContext.data = data;

	UpdateUrl();
}

function ChangeContext_Page(page) {
	// alert('ChangeContext_Page(' + page + ')');

	currentContext.page = page;

	UpdateUrl();
}

// Load page according to the current context
function LoadPage() {
	// alert('LoadPage() : ' + 'page=' + currentContext.page + ', ' + 'area=' + currentContext.area + ', id=' + currentContext.id + ', data=' + currentContext.data);

	$('#content').html('<img src="../media/loading.gif" />');

    LoadLeftMenu();
	LoadTopMenu();
	$.ajax({
        type : 'POST',
        url : 'page.php?page=' + currentContext.page + '&area=' + currentContext.area + '&id=' + currentContext.id + '&data=' + currentContext.data,
        data: {
            'page': currentContext.page, 
            'area': currentContext.area,
            'id': currentContext.id,
            'data': currentContext.data,
        },
        dataType: 'html',
        success : function(data) {
            $('#content').html(data);
        }
    });
}

// ========== Menus management

function LoadTopMenu() {
	$.ajax({
        type : 'POST',
        url : 'menu_top_1st_line.php',
        data: {
            'page': currentContext.page, 
            'area': currentContext.area,
            'id': currentContext.id,
            'data': currentContext.data,
        },
        dataType: 'html',
        success : function(data) {
            $('#topMenu').html(data);
        }
    });

	$.ajax({
        type : 'POST',
        url : 'menu_top_2nd_line.php',
        data: {
            'page': currentContext.page, 
            'area': currentContext.area,
            'id': currentContext.id,
            'data': currentContext.data,
        },
        dataType: 'html',
        success : function(data) {
            $('#topSecondLineMenu').html(data);
        }
    });
}

function LoadLeftMenu()
{
	$('#leftMenu').html('');
	$.ajax({
        type : 'POST',
        url : 'menu_left.php',
        data: {
            'page': currentContext.page, 
            'area': currentContext.area,
            'id': currentContext.id,
            'data': currentContext.data,
        },
        dataType: 'html',
        success : function(data) {
            $('#leftMenu').html(data);
        }
    });
}

//========== Common functions

function GetDecimalValue(text) {
	var value = 0; 

	text = text.replace(' ','');
	text = text.replace(',','.');
	text = text.replace('€','');

	if (!isNaN(parseFloat(text))) {
		value = parseFloat(text);
	}

	return value;
}