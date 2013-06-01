<?php
include_once '../security/security_manager.php';
include_once '../dal/dal_appzone.php';
?>
<!DOCTYPE>
<html>
<head>
<title>Steve Fuchs AppZone - Applications en ligne</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="expires" content="0">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache, must-revalidate">
<meta name="Description" content="Applications conçues par Steve Fuchs (gestion financière du couple, gestionnaire de relations personnelles ou privées, générateur de mots de passe, bloc-notes en ligne)">
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<link rel="stylesheet" type="text/css" href="index.css" />
</head>
<body>
Menu principal
&nbsp;/&nbsp;<?php echo $FULL_NAME; ?>
<?php
if (!$SECURITY_SINGLE_USER_MODE)
{
?>
&nbsp;/&nbsp;<a href="disconnection.php">Se séconnecter</a>
<?php
}
?>
&nbsp;/&nbsp;<a href="profile.php">Modifier son profil</a>
&nbsp;/&nbsp;<a href="copyright.htm" target="blank">Mentions légales - Copyright</a>
<br />
<br />
<img src="prm.ico" />
<a href="../prm/">Gestionnaire de relations personnelles ou privées</a> (projet PRM - Private Relationship manager)
<br />
<img src="gfc.ico" />
<a href="../gfc">Gestion Financière du Couple / Compatibilité de couple</a> (projet GFC)
<br />
<img src="unp.ico" />
<a href="../unp">Bloc-notes</a> (projet UNP - Universal NotePad)
<br />
<img src="pwd.ico" />
<a href="../pwd/">Générateur de mots de passe</a> (projet PWD - PassWord Generator)
<br />
<br /> 
<br />
<?php
if (IsReadOnly())
{
?>
Votre dernière connection : <?php
$result = security_GetLastConection($_SESSION['user_id']);
$row = mysql_fetch_assoc($result);
echo $row["connection_date_time"];
?><br />
<?php
}
else
{
?>
Vos dernières connections :
<br />
<table id="standardTable">
<thead>
<tr class="tableRowTitle">
<th>Date et heure</th>
  <?php if (!$SECURITY_SINGLE_USER_MODE)
  {?>
<th>IP</th>
<?php }?>
<th>Navigateur</th>
</tr>
</thead>
<tbody>
<?php
$result = security_GetLastConections($_SESSION['user_id']);
$n = mysql_num_rows($result);
for ($i = 0; $i < $n; $i++)
{
  $row = mysql_fetch_assoc($result);
  ?>

  <tr class="tableRow<?php if ($i % 2 == 0) echo '0'; else echo '1'; ?>">
  <td>
  <?php echo $row["connection_date_time"]; ?>
  </td>
  <?php if (!$SECURITY_SINGLE_USER_MODE)
  {?>
  <td>
  <?php echo $row["ip_address"]; ?>
  </td>
  <?php } ?>
  <td>
  <?php echo $row["browser"]; ?>
  </td>
  </tr>
  <?php
}
?>
</tbody>
</table>
<?php
}
?>
<br />
Un peu de publicité (il faut bien payer l'hébergement...) :<br />
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
</body>
</html>