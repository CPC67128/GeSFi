<?php
include_once '../security/security_manager.php';

?>

<?php if (!($_SESSION['page'] == 'dashboard' || ($_SESSION['page'] == 'records' && $_SESSION['account_id'] == ''))) { ?><a href="#" onclick="javascript:ChangeContext('dashboard','',''); return false;"><?php } ?>Gestion courante<?php if (!($_SESSION['page'] == 'dashboard' || ($_SESSION['page'] == 'records' && $_SESSION['account_id'] == ''))) { ?></a><?php } ?>
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

if ($_SESSION['page'] != 'configuration')
	echo '<a href="#" onclick="javascript:ChangeContext(\'configuration\', \'\',\'configuration\'); return false;">';
echo 'Configuration';
if ($_SESSION['page'] != 'configuration')
	echo '</a>';
?>