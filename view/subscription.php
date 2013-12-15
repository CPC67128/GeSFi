<!DOCTYPE html>
<html>
<head>
<title>BudgetFox</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="expires" content="0">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache, must-revalidate">
<meta name="Description" content="Applications conçues par Steve Fuchs (gestion financière du couple, gestionnaire de relations personnelles ou privées, générateur de mots de passe, bloc-notes en ligne)">
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script language="javascript" src="../3rd_party/md5.js"></script>
<style type="text/css">
body {
	font: 80% "Trebuchet MS", sans-serif;
	margin: 0px;
}

div.centered{
	display:block;
	
	position:absolute;
	top:40%;
	left:35%;
	width:400px;
}

a:link {text-decoration: none}
a:visited {text-decoration: none}
a:active {text-decoration: none}
a:hover {text-decoration: none; color: red;}
</style>
<script>
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
	              if (status == 'success')
	              {
	            	  $("#subscriptionFormResult").html(response);
	                  if (response.indexOf("<!-- ERROR -->") >= 0)
	                  {
	                  }
	                  else
	                  {
	                      window.location="login.php";
	                  }
	              }
	              else
	              {
	                  $("#subscriptionFormResult").html(CreateUnexpectedErrorWeb("Status = " + status));
	              }

		setTimeout(function(){
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
		window.location="login.php";
	}); 
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

function CreateErrorWeb($error)
{
	var html = '<div class="ui-widget">';
	html += '<div class="ui-state-error ui-corner-all" style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em;">';
	html += '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
	html += '<strong>' + $error + '</strong></p>';
	html += '</div></div>';
	return html;
}
</script>
</head>

<body>
<h1>Formulaire d'inscription</h1>
<form id="subscriptionForm" action="/">
<table>
<tr>
<td>Email</td>
<td><input type="text" name="email" size="20" autocomplete="off" value="" /> <font color="red"><strong>*</strong></font></td>
</tr>
<tr>
<td>Nom complet</td>
<td><input type="text" name="name" size="20" autocomplete="off" value="" /></td>
</tr>
<tr>
<td>Mot de passe</td>
<td><input id="password" type="password" name="password" size="20" autocomplete="off" value="" /> <font color="red"><strong>*</strong></font></td>
</tr>
<tr>
<td>Confirmation</td>
<td><input id="passwordConfirmation" type="password" name="passwordConfirmation" size="20" autocomplete="off" value="" /> <font color="red"><strong>*</strong></font></td>
</tr>
<tr>
<td><i>Hash</i></td>
<td><input id="passwordMD5" style='background-color : #d1d1d1;' readonly="readonly" type="text" name="passwordMD5" size="20" autocomplete="off" value="" /></td>
</tr>
</table>
<br />
Vérification captcha : <font color="red"><strong>*</strong></font><br />
<?php
require_once('../3rd_party/recaptcha-php/recaptchalib.php');
$publickey = "6Ld6LNYSAAAAAPOsMcXZymlFhIevhg8UEnY2eE_D";
echo recaptcha_get_html($publickey);
?>
<br />
<input value="S'enregistrer" name="submit" type="submit">
&nbsp;&nbsp;
<input value="Annuler" id="cancelButton" type="button">

<div id="subscriptionFormResult"></div>
</form>

</body>
</html>