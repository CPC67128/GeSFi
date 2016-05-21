function HashPassword() {
	pw = $("#password").val();
	md5 = MD5(pw);
	$("#passwordMD5").val(md5);
}

$(function() {
	$('#password').keyup(function() {
		HashPassword();
	});

	$("#saasLoginForm").submit(function() {
		HashPassword();
		$("#password").val('');

		$.post(
				'../controller/controller.php?action=user_login',
				$(this).serialize(),
				function(response, status){
					$("#saasLoginFormResult").stop().show();
					if (status == 'success') {
						$("#saasLoginFormResult").html(response);
						if (response.indexOf("<!-- ERROR -->") < 0) {
							window.location="index.php";
						}
					}
					else {
						$("#saasLoginFormResult").html(CreateUnexpectedErrorWeb("Status = " + status));
					}

					setTimeout(function() {
						$("#saasLoginFormResult").fadeOut("slow", function () {
							$('#saasLoginFormResult').empty();
						})
					}, 4000);
				}
		);
		return false;
	});

	$("#subscriptionButton").click( function () {
		window.location="page_subscription.php";
	});

	HashPassword();
});

function CreateUnexpectedErrorWeb($error)
{
	var html = '<div class="ui-widget">';
	html += '<div class="ui-state-error ui-corner-all" style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em;">';
	html += '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
	html += '<strong>Unexpected error</strong>' + $error + '</p>';
	html += '</div></div>';
	return html;
}