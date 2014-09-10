<?php
include '../component/component_autoload.php';

$usersHandler = new UsersHandler();

$usersHandler->StartSession();
$isSessionUserSet = $usersHandler->IsSessionUserSet();

$action = $_GET['action'];

try
{
	$operation = null;

	$operationClassName = 'Operation_'.str_replace(' ', '_', ucwords(str_replace('_', ' ', $action)));
	$operation = new $operationClassName();

	if ($operation == null)
		throw new Exception("Aucune opération associée");

	if ($operation->IsSessionRequired() && !$isSessionUserSet)
		throw new Exception("Une session utilisateur doit être démarré");

	$operation->hydrate($_POST);
	$operation->Execute();
}
catch (Exception $e)
{
	ReturnError($e->getMessage());
}

ReturnSuccess();

function ReturnError($error)
{
	?>
	<!-- ERROR -->
	<div class="ui-widget">
	<div class="ui-state-error ui-corner-all" style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em;">
	<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
	<strong>Erreur : </strong><?php echo $error; ?></p>
	</div>
	</div>
	<?php
	exit();
}

function ReturnSuccess()
{
	?>
	<div class="ui-widget">
	<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em;">
	<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
	Enregistré!</p>
	</div>
	</div>
	<?php
	exit();
}