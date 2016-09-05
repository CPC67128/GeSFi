<?php include 'page_login_logic.php'; ?>
<!doctype html>
<html>
<head>
<?php include '../component/component_head.php'; ?>
<title><?= $translator->getTranslation("GeSFi / Login") ?></title>
<link href="gesfi_login.css" rel="stylesheet" />
<script src="../3rd_party/md5.js"></script>

</head>
<body>
<table width="100%">
<tr>
<td valign="top" align="left">
<img src="../media/gfc.ico" /> GeSFi par <a href="http://stevefuchs.fr/">Steve Fuchs</a><br />
<br />
<a href="copyright.htm" target="blank">Licence et droit d’auteur</a>
</td>
<td valign="top" align="right">
<!-- Ad placeholder -->
</td>
</tr>
</table>
<br/>
<div class="centered">
<form id="saasLoginForm" action="/">
<table>

<tr>
<td colspan="2">
<table>
<tr>
	<td>Utilisateur</td>
	<td>
	<input type="radio" name="email" value="Homme" checked> Homme<br>
	<input type="radio" name="email" value="Femme"> Femme<br>
	</td>
</tr>
<tr>
	<td>Mot de passe</td>
	<td><input type="password" name="password" id="password" size="35" /></td>
</tr>
<tr>
    <td><i>Code de sécurité</i></td>
    <td><i><input id="passwordMD5" style="background-color : #d1d1d1;" readonly="readonly" type="text" name="passwordMD5" size="35" autocomplete="off" value="" /></i></td>
</tr>
</table>
</td>
</tr>

<tr>
<td align="right"><input value="Se connecter" name="submit" type="submit"></td>
</tr>

<tr>
<td colspan="2"><div id="saasLoginFormResult"></div></td>
</tr>

</table>
</form>

</div>
</body>

<script>


function HashPassword() {
	pw = $("#password").val();
	md5 = MD5(pw);
	$("#passwordMD5").val(md5);
}

$(function () {
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
});

HashPassword();

function CreateUnexpectedErrorWeb($error)
{
	var html = '<div class="ui-widget">';
	html += '<div class="ui-state-error ui-corner-all" style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em;">';
	html += '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
	html += '<strong>Unexpected error</strong>' + $error + '</p>';
	html += '</div></div>';
	return html;
}
</script>

</html>