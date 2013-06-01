<?php
die('En construction...');
include_once '../security/security_manager.php';
include_once '../dal/dal_gfc.php';

function __autoload($class_name)
{
	include './class/'.$class_name . '.php';
}

$currentConfiguration = new Configuration();
$currentConfiguration->Get();

$row = GetConfiguration();
?>
<h1>Configuration de l'application</h1>
<form action="/" id="configurationForm">
Acteur 1 <input type="text" name="actor1" size="20" value="<?php echo $currentConfiguration->getActor1(); ?>">
/ Email <input type="text" name="actor1Email" size="40" value="<?php echo $currentConfiguration->getActor1Email(); ?>">
<br />
Acteur 2 <input type="text" name="actor2" size="20" value="<?php echo $currentConfiguration->getActor2(); ?>">
/ Email <input type="text" name="actor2Email" size="40" value="<?php echo $currentConfiguration->getActor2Email(); ?>">
<br />
<br />
Culture <input type="text" name="culture" size="20" readonly="readonly" value="<?php echo $currentConfiguration->getCulture(); ?>"><br />
<br />
<?php
for ($i = 1; $i <= 15; $i++)
{
?>
Catégorie <?php echo $i; ?> <input type="text" name="category<?php echo $i; ?>" size="40" value="<?php echo $currentConfiguration->getCategory($i); ?>"><br />
<?php
}
?>
<br />
Utilisation d'un compte courant réel : <input type="text" name="realJointAccountUse" size="2" value="<?php echo $currentConfiguration->getRealJointAccountUse(); ?>"> (0 ou 1)<br />
Solde minimum nécéssaire : <input type="text" name="jointAccountExpectedMinimumBalance" size="7" value="<?php echo $currentConfiguration->getJointAccountExpectedMinimumBalance(); ?>">&nbsp;&euro;<br />
Versement sur le compte commun minimum : <input type="text" name="jointAccountExpectedMinimumCredit" size="7" value="<?php echo $currentConfiguration->getJointAccountExpectedMinimumCredit(); ?>">&nbsp;&euro;<br />
Supplément de versement maximum par acteur : <input type="text" name="jointAccountMaximumActorExtraCredit" size="7" value="<?php echo $currentConfiguration->getJointAccountMaximumActorExtraCredit(); ?>">&nbsp;&euro;<br />
<br />
Part de l'acteur 1 par défaut : <input type="text" name="actor1DefaultChargeLevel" size="2" value="<?php echo $currentConfiguration->getActor1DefaultChargeLevel(); ?>"> (>= 0 et <= 100)<br />
<br />
<input value="Mettre à jour" id="submitConfiguration" type="submit">&nbsp;&nbsp;<input type="reset" value="Effacer"><br />
<div id='configurationFormResult'></div>
</form>
<script type='text/javascript'>
$("#configurationForm").submit( function () {
	document.getElementById("submitConfiguration").disabled = true;
	$.post (
		'controller.php?action=configuration',
		$(this).serialize(),
		function(response, status) {
			$("#configurationFormResult").stop().show();
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					$("#configurationFormResult").html(response);
				}
				else {
					document.getElementById("submitConfiguration").disabled = false;
					LoadConfigurationPage();
				}
			}
			else {
				$("#configurationFormResult").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitConfiguration").disabled = false;

			setTimeout(function() {
				$("#configurationFormResult").fadeOut("slow", function () {
					$('#configurationFormResult').empty();
				})
			}, 4000);
		}
	);
	return false;
});
</script>

<h1>Maintenance : inversion de catégories</h1>

<form action="/" id="maintenanceReverseCategoryForm">
Inverser la catégorie numéro <input type="text" name="categoryA" size="4">
et la catégorie numéro <input type="text" name="categoryB" size="4">
<input value="Faire l'action" id="submitReverseCategory" type="submit">&nbsp;&nbsp;<input id="cancel" type="reset" value="Effacer" ><br />
<div id='maintenanceReverseCategoryFormResult'></div>
</form>
<script type='text/javascript'>
$("#maintenanceReverseCategoryForm").submit( function () {
	document.getElementById("submitReverseCategory").disabled = true;
	$.post (
		'controller.php?action=reverseCategory',
		$(this).serialize(),
		function(response, status) {
			$("#maintenanceReverseCategoryFormResult").stop().show();
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					$("#maintenanceReverseCategoryFormResult").html(response);
				}
				else {
					document.getElementById("submitReverseCategory").disabled = false;
					LoadConfigurationPage();
				}
			}
			else {
				$("#maintenanceReverseCategoryFormResult").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitReverseCategory").disabled = false;

			setTimeout(function() {
				$("#maintenanceReverseCategoryFormResult").fadeOut("slow", function () {
					$('#maintenanceReverseCategoryFormResult').empty();
				})
			}, 4000);
		}
	);
	return false;
});
</script>
