<?php
include_once '../security/security_manager.php';
include_once '../dal/dal_appzone.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>AppZone SaaS by Steve Fuchs / Subscription</title>
<meta http-equiv="expires" content="0">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache, must-revalidate">
<link href="prm.ico" rel="icon" type="image/x-icon" />
<script type="text/javascript" src="<?php echo $THIRD_PARTY_FOLDER; ?>jquery/js/jquery-1.8.0.min.js"></script>
<script language="javascript" src="<?php echo $THIRD_PARTY_FOLDER; ?>md5.js"></script>
<link type="text/css" href="index.css" rel="stylesheet" />  
<script>
$(function() {
    $('#password').keyup(function(){
        pw = $("#password").val();
        md5 = MD5(pw);
        $("#passwordMD5").val(md5);
    });

	$("#profileForm").submit( function () {
		if ($("#password").val() != '' && $("#password").val() != $("#passwordConfirmation").val())
		{
            $("#profileFormResult").stop().show();
			$("#profileFormResult").html(CreateErrorWeb("Le nouveau mot de passe n'est pas confirmé !"));
			setTimeout(function() {
			    $("#profileFormResult").fadeOut("slow", function () {
			        $('#profileFormResult').empty();
                })
            }, 4000);
		}
		else
		{
	        pw = $("#password").val();
	        if (pw != '')
	        {
		        md5 = MD5(pw);
		        $("#passwordMD5").val(md5);
		        $("#password").val('');
	        }
	
            $.post(
	          'update_action.php',
	          $(this).serialize(),
	          function(response, status){
	        	  $("#profileFormResult").stop().show();
	              if (status == 'success')
	              {
	            	  $("#profileFormResult").html(response);
	                  if (response.indexOf("<!-- ERROR -->") >= 0)
	                  {
	                  }
	                  else
	                  {
	                      window.location="index.php";
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

	$("#cancel").click( function () {
		window.location="index.php";
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
<h1>Mise à jour de votre profil</h1>
<form id="profileForm" action="/">
<?php
$row = security_GetUserRow($USER_ID);
?>
<table>
<tr>
	<td style="vertical-align: middle;">
	Email
	</td>
	<td>
	<input type="text" name="email" size="40" autocomplete="off" value="<?php echo $row['email']; ?>" /> <font color="red"><strong>*</strong></font>
	</td>
</tr>
<tr>
	<td style="vertical-align: middle;">
	Nom complet
	</td>
	<td>
	<input type="text" name="fullName" size="40" autocomplete="off" value="<?php echo $row['full_name']; ?>" />
	</td>
</tr>
<tr>
	<td style="vertical-align: middle;">
	Nouveau mot de passe
	</td>
	<td>
	<input id="password" type="password" name="password" size="20" autocomplete="off" value="" /> <font color="red">(laisser vide si vous ne voulez pas changer votre mot de passe)</font>
	</td>
</tr>
<tr>
	<td style="vertical-align: middle;">
	Confirmation
	</td>
	<td>
	<input id="passwordConfirmation" type="password" name="passwordConfirmation" size="20" autocomplete="off" value="" />
	</td>
</tr>
<tr>
	<td style="vertical-align: middle;">
	Code de sécurité
	</td>
	<td>
	<input id="passwordMD5" readonly="readonly" type="text" name="passwordMD5" size="30" autocomplete="off" value="" />
	</td>
</tr>
</table>
<br />
<input value="Enregistrer" name="submit" type="submit">
&nbsp;&nbsp;
<input value="Annuler" id="cancel" type="button">

<div id="profileFormResult"></div>
</form>

</body>
</html>