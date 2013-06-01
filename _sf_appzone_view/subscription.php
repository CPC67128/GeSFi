<?php include '../configuration/3rd_party.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Steve Fuchs AppZone - Applications en ligne / Inscription</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="expires" content="0">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache, must-revalidate">
<meta name="Description" content="Applications conçues par Steve Fuchs (gestion financière du couple, gestionnaire de relations personnelles ou privées, générateur de mots de passe, bloc-notes en ligne)">
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<link type="text/css" href="<?php echo $THIRD_PARTY_FOLDER; ?>jquery/css/smoothness/jquery-ui-1.8.23.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="<?php echo $THIRD_PARTY_FOLDER; ?>jquery/js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?php echo $THIRD_PARTY_FOLDER; ?>jquery/js/jquery-ui-1.8.23.custom.min.js"></script>
<script language="javascript" src="<?php echo $THIRD_PARTY_FOLDER; ?>md5.js"></script>
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

/*
.centered {
  position: fixed;
  top: 50%;
  left: 50%;
  margin-top: -100px;
  margin-left: -150px;
}
*/

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
		if ($("#password").val() != $("#passwordConfirmation").val())
		{
			$("#subscriptionFormResult").html(CreateErrorWeb("Le mot de passe n'est pas confirmé!"));
			$("#subscriptionFormResult").stop().show();
			setTimeout(function(){
          		$("#subscriptionFormResult").fadeOut("slow", function () {
        			$('#subscriptionFormResult').empty();
          		})
       		}, 4000);
		}
		else
		{
	        pw = $("#password").val();
	        md5 = MD5(pw);
	        $("#passwordMD5").val(md5);
	        $("#password").val('');

	        $.post(
	          'subscription_action.php',
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
		}

        return false;
      });

	$("#cancelButton").click( function () {
		window.location="../pages/login.php";
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
<h1>Inscription</h1>
<form id="subscriptionForm" action="/">
<table>
<tr>
	<td style="vertical-align: middle;">
	Email
	</td>
	<td>
	<input type="text" name="email" size="20" autocomplete="off" value="" /> <font color="red"><strong>*</strong></font>
	</td>
</tr>
<tr>
	<td style="vertical-align: middle;">
	Nom complet
	</td>
	<td>
	<input type="text" name="fullName" size="20" autocomplete="off" value="" />
	</td>
</tr>
<tr>
	<td style="vertical-align: middle;">
	Mot de passe
	</td>
	<td>
	<input id="password" type="password" name="password" size="20" autocomplete="off" value="" /> <font color="red"><strong>*</strong></font>
	</td>
</tr>
<tr>
	<td style="vertical-align: middle;">
	Confirmation
	</td>
	<td>
	<input id="passwordConfirmation" type="password" name="passwordConfirmation" size="20" autocomplete="off" value="" /> <font color="red"><strong>*</strong></font>
	</td>
</tr>
<tr>
	<td style="vertical-align: middle;">
	Mot de passe MD5
	</td>
	<td>
	<input id="passwordMD5" readonly="readonly" type="text" name="passwordMD5" size="20" autocomplete="off" value="" /> <font color="red"><strong>*</strong></font>
	</td>
</tr>
</table>
<br />
Vérification captcha : <font color="red"><strong>*</strong></font><br />
<?php
require_once($THIRD_PARTY_FOLDER.'recaptcha-php/recaptchalib.php');
$publickey = "6Ld6LNYSAAAAAPOsMcXZymlFhIevhg8UEnY2eE_D"; // you got this from the signup page
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