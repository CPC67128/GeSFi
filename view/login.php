<?php
//include_once('../dal/dal_appzone.php');
include_once('../security/mailing.php');

function __autoload($class_name)
{
	$file = '../controller/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../model/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../security/'.$class_name . '.php';
	include $file;
}

$usersHandler = new UsersHandler();
$securityHandler = new SecurityHandler();

/***** Single User Security Mode *****/
// In this mode, the user is automaticaly loged in using the user id configured in the configuration

if ($securityHandler->IsSingleUserMode())
{
	session_start();

	$user_id = $securityHandler->GetSingleUserModeUserId();
	$user = $usersHandler->GetUser($user_id);
	$_SESSION['email'] = $user->get('email');
	$_SESSION['user_id'] = $user->get('userId');
	$_SESSION['full_name'] = $user->get('name');
	$_SESSION['read_only'] = $user->get('readOnly');

	$usersHandler->RecordUserConnection($user->get('userId'), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);

	header("HTTP/1.1 301 Moved Permanently");
	if (isset($_SESSION['go_to']) && $_SESSION['go_to'] != '')
	{
		header("Location: ".$_SESSION['go_to']);
		$_SESSION['go_to'] = '';
	}
	else
		header("Location: index.php");

	exit();
}

/***** Multiple User Security Mode *****/

session_start();

/*** User already connected ***/

if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0)
{
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: index.php");

    exit();
}

/*** Auto login mode ***/
// In this mode, the user id is given as a GET parameter, the password must be set to ''
if (isset($_GET['autologin']) && strlen($_GET['autologin']) > 0)
{
	$user = $usersHandler->GetUser($_GET["autologin"]);

	if (!$user->IsNull())
	{
		if ($user->IsPasswordCorrect("d41d8cd98f00b204e9800998ecf8427e"))
		{
			$_SESSION['email'] = $user->get('email');
			$_SESSION['user_id'] = $user->get('userId');
			$_SESSION['full_name'] = $user->getName();
			$_SESSION['read_only'] = $user->get('readOnly');
	
			$user->RecordConnection($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
	
			SendEmailToAdministrator("Nouvelle connection", "Nouvelle connection en autlogin de ".$user->getName());
		}

		header("Location: index.php");
		exit();
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<title>BudgetFox</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="expires" content="0">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache, must-revalidate">
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
	left:25%;
	width:500px;
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

	$("#saasLoginForm").submit( function () {
        pw = $("#password").val();
        md5 = MD5(pw);
        $("#passwordMD5").val(md5);
        $("#password").val('');

        $.post(
          '../controller/controller_login.php?action=user_login',
          $(this).serialize(),
          function(response, status){
        	  $("#saasLoginFormResult").stop().show();
              if (status == 'success')
              {
                  $("#saasLoginFormResult").html(response);
                  if (response.indexOf("<!-- ERROR -->") < 0)
                  {
                	  window.location="index.php";
                  }
              }
              else
              {
                  $("#saasLoginFormResult").html(CreateUnexpectedErrorWeb("Status = " + status));
              }

            	setTimeout(function(){
              		$("#saasLoginFormResult").fadeOut("slow", function () {
            			$('#saasLoginFormResult').empty();
              		})
           		}, 4000);
          }
        );
        return false;
      });

	$("#subscriptionButton").click( function () {
		window.location="subscription.php";
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
</script>
</head>

<body>
<table width="100%">
<tr>
<td valign="top" align="left">
<img src="../media/gfc.ico" />&nbsp;BudgetFox par <a href="http://stevefuchs.fr/">Steve Fuchs</a>
<br />
<br />
<a href="copyright.htm" target="blank">Mentions légales - Copyright</a>
</td>
<td valign="top" align="right">
<a href="http://1and1.fr/xml/order?k_id=16605005" target="_blank"><img src="http://adimg.uimserv.net/1und1/Werbemittel_FR/wh_an_468x60.gif" width="468" height="60"  border="0"/></a>
<br />
<script type="text/javascript"><!--
google_ad_client = "ca-pub-2289321929506081";
/* AppZone */
google_ad_slot = "0039226258";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</td>
</tr>
</table>
<br/>
<div class="centered">
<form id="saasLoginForm" action="/">
<table>
<tr>
	<td style="vertical-align: middle;">
	Email
	</td>
	<td>
	<input type="text" name="email" size="35" />
	</td>
</tr>
<tr>
	<td style="vertical-align: middle;">
	Mot de passe
	</td>
	<td>
	<input type="password" name="password" id="password" size="35" />
	</td>
</tr>
<tr>
    <td style="vertical-align: middle;">
    <i>Code de sécurité</i>
    </td>
    <td>
    <input id="passwordMD5" readonly="readonly" type="text" name="passwordMD5" size="35" autocomplete="off" value="" />
    </td>
</tr>
</table>
<br />
<input value="Se connecter" name="submit" type="submit">
&nbsp;&nbsp;
<input value="S'inscrire" id="subscriptionButton" type="button">

<div id="saasLoginFormResult"></div>
</form>

</div>
</body>
</html>