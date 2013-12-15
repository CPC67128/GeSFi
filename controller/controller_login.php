<?php
function __autoload($class_name)
{
	$file = '../controller/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../model/'.$class_name . '.php';
	include $file;
}

try
{
	$operation = null;

	switch ($_GET['action'])
	{
		case 'user_subscription':
			$operation = new Operation_User_Subscription();
			break;
		case 'user_login':
			$operation = new Operation_User_Login();
			break;
	}

	if ($operation == null)
		throw new Exception("Aucune opération associée");

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