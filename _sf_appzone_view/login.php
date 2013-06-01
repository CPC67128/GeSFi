<?php
include_once('../dal/dal_appzone.php');
include_once('mailing.php');

if ($SECURITY_SINGLE_USER_MODE)
{
	session_start();

	$user_id = $SECURITY_SINGLE_USER_MODE_USER_ID;
	$row = security_GetUserRow($user_id);
	$_SESSION['email'] = $row['email'];
	$_SESSION['user_id'] = $user_id;
	$_SESSION['full_name'] = $row['full_name'];
	$_SESSION['read_only'] = $row['read_only'];
	security_RecordUserConnection($user_id, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);

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

session_start();

if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0)
{
    // If the user is already connected, we display the information and redirect him to index.php
?>
<html>
<header>
<meta charset="utf-8">
<meta http-equiv="expires" content="0">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache, must-revalidate">
<script type="text/javascript">
window.location = 'index.php';
</script>
</header>
<body>
Vous êtes déjà connecté en tant que <?php echo $_SESSION['full_name']; ?>.
<br />
Redirection vers le <a href="index.php">menu principal</a> en cours.
</body>
</html>
<?php
    exit();
}

// ================================================================================================================================================
// Auto login mode
if (isset($_GET['autologin']) && strlen($_GET['autologin']) > 0)
{
	if (security_IsPasswordCorrect($_GET["autologin"], "d41d8cd98f00b204e9800998ecf8427e"))
	{
		$user_id = security_GetUserId($_GET["autologin"]);
		$row = security_GetUserRow($user_id);

		$_SESSION['email'] = $_GET["autologin"];
		$_SESSION['user_id'] = $user_id;
		$_SESSION['full_name'] = $row['full_name'];
		security_RecordUserConnection($user_id, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);

		SendEmailToAdministrator("Nouvelle connection à guest", "Nouvelle connection d'un utilisateur au lien de démonstration");
	}
?>
<html>
<header>
<meta charset="utf-8">
<meta http-equiv="expires" content="0">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache, must-revalidate">
<script type="text/javascript">
window.location = 'index.php';
</script>
</header>
<body>
Vous êtes déjà connecté en tant que <?php echo $_SESSION['full_name']; ?>.
<br />
Redirection vers le <a href="index.php">menu principal</a> en cours.
</body>
</html>
<?php
exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Steve Fuchs AppZone - Applications en ligne</title>
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
          'login_action.php',
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
AppZone par <a href="http://stevefuchs.fr/">Steve Fuchs</a>&nbsp;:
<br />
<img src="prm.ico" />Gestionnaire de relations personnelles ou privées
<br />
<img src="gfc.ico" />
Gestion Financière du Couple / Compatibilité de couple
<br />
<img src="unp.ico" />
Bloc-notes
<br />
<img src="pwd.ico" />
Générateur de mots de passe
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