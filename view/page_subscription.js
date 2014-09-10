$(function() {

	$('#password').keyup(function(){
		pw = $("#password").val();
		md5 = MD5(pw);
		$("#passwordMD5").val(md5);
	});

	$("#subscriptionForm").submit( function () {
		pw = $("#password").val();
		md5 = MD5(pw);
		$("#passwordMD5").val(md5);

		$.post(
				'../controller/controller_login.php?action=user_subscription',
				$(this).serialize(),
				function(response, status){
					$("#subscriptionFormResult").stop().show();
					if (status == 'success') {
						$("#subscriptionFormResult").html(response);
						if (response.indexOf("<!-- ERROR -->") >= 0) {
						}
						else {
							window.location="page_login.php";
						}
					}
					else {
						$("#subscriptionFormResult").html(CreateUnexpectedErrorWeb("Status = " + status));
					}

					setTimeout(function() {
						$("#subscriptionFormResult").fadeOut("slow", function () {
							$('#subscriptionFormResult').empty();
						})
					}, 4000);
				}
		);

		Recaptcha.reload();

		return false;
	});

	$("#cancelButton").click( function () {
		window.location="page_login.php";
	}); 
});

function CreateUnexpectedErrorWeb($error) {
	var html = '<div class="ui-widget">';
	html += '<div class="ui-state-error ui-corner-all" style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em;">';
	html += '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
	html += '<strong>Unexpected error</strong>' + $error + '</p>';
	html += '</div></div>';
	return html;
}

function CreateErrorWeb($error) {
	var html = '<div class="ui-widget">';
	html += '<div class="ui-state-error ui-corner-all" style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em;">';
	html += '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
	html += '<strong>' + $error + '</strong></p>';
	html += '</div></div>';
	return html;
}