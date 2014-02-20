<?php
include_once '../security/security_manager.php';

function __autoload($class_name)
{
	$file = '../controller/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../model/'.$class_name . '.php';
	include $file;
}

?>

<?php if ($_SESSION['page'] != 'dashboard') { ?><a href="#" onclick="javascript:ChangeContext('dashboard','',''); return false;"><?php } ?>Gestion courante<?php if ($_SESSION['page'] != 'dashboard') { ?></a><?php } ?>
 / 
<?php
$accountsManager = new AccountsManager();
$accounts = $accountsManager->GetAllOrdinaryAccounts();

foreach ($accounts as $account)
{
	if ($account->get('accountId') != $_SESSION['account_id'])
		echo '<a href="#" onclick="javascript:ChangeContext(\'records\', \''.$account->get('accountId').'\',\'\'); return false;">';
	echo $account->get('name');
	if ($account->get('accountId') != $_SESSION['account_id'])
		echo '</a>';
	echo ' / ';
}

if ($_SESSION['account_id'] != 'configuration')
	echo '<a href="#" onclick="javascript:ChangeAccount(\'configuration\'); return false;">';
echo 'Configuration';
if ($_SESSION['account_id'] != 'configuration')
	echo '</a>';
?>