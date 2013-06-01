<?php
session_start();
include_once '../dal/dal_appzone.php';
include_once('../_sf_appzone_view/mailing.php');

function RedirectToLoginPage()
{
?>
AppZone par <a href="http://stevefuchs.fr">Steve Fuchs</a>
<br />
<br />
Merci de vous <a href="../_sf_appzone_view/login.php">connecter</a> d'abord.
<script type="text/javascript">
window.location='../_sf_appzone_view/login.php';
</script>
<?php
exit();
}

function RedirectToPage($url)
{
?>
AppZone par <a href="http://stevefuchs.fr">Steve Fuchs</a>
<br />
<br />
Merci de vous <a href="../_sf_appzone_view/login.php">connecter</a> d'abord.
<script type="text/javascript">
window.location='<?php echo $url; ?>';
</script>
<?php
exit();
}

if (!isset($_SESSION['user_id']))
{
	if (isset($_GET['autologin']) && strlen($_GET['autologin']) > 0 && security_IsPasswordCorrect($_GET["autologin"], "d41d8cd98f00b204e9800998ecf8427e"))
	{
		$user_id = security_GetUserId($_GET["autologin"]);
		$row = security_GetUserRow($user_id);

		$_SESSION['email'] = $_GET["autologin"];
		$_SESSION['user_id'] = $user_id;
		$_SESSION['full_name'] = $row['full_name'];
		$_SESSION["read_only"] = $row['read_only'];
		security_RecordUserConnection($user_id, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);

		SendEmailToAdministrator("Nouvelle connection à guest", "Nouvelle connection d'un utilisateur au lien de démonstration");

		$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		RedirectToPage($url);
	}
	else
	{
		if ($SECURITY_SINGLE_USER_MODE)
		{
			$_SESSION["go_to"] = $_SERVER['REQUEST_URI'];
			RedirectToLoginPage();
		}
		else
			RedirectToLoginPage();
	}
}
else
{
	$EMAIL = $_SESSION["email"];
	$USER_ID = $_SESSION["user_id"];
	$FULL_NAME = $_SESSION["full_name"];
	$READ_ONLY = $_SESSION["read_only"];
}

define('EMAIL', $EMAIL);
define('USER_ID', $USER_ID);
define('FULL_NAME', $FULL_NAME);
define('READ_ONLY', $READ_ONLY);
