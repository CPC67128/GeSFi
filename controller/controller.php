<?php
include_once '../security/security_manager.php';

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
		case 'record_remark':
			$operation = new Operation_Record_Remark();
			break;
		case 'record_delete':
			$operation = new Operation_Record_Delete();
			break;
		case 'record_transfer':
			$operation = new Operation_Record_Transfer();
			break;
		case 'record_income':
			$operation = new Operation_Record_Income();
			break;
		case 'record_expense':
			$operation = new Operation_Record_Expense();
			break;
				
		case 'account_change':
			$operation = new Operation_Account_Change();
			break;

/*		case 'remarkInvestment':
			$operation = new Operation_InvestmentRecord_Remark();
			break;

		case 'reverseCategory':
			$newAction = new Action_ReverseCategory();
			$newAction->hydrate($_POST);
			$newAction->Execute();
			break;
		case 'sendIncomeRequest':
			$newAction = new Action_SendIncomeRequest();
			$newAction->hydrate($_POST);
			$newAction->Execute();
			break;
		case 'accountModification':
			$newAction = new Action_AccountModification();
			$newAction->hydrate($_POST);
			$newAction->Execute();
			break;
		case 'userCategoryModification':
			$newAction = new Action_UserCategoryModification();
			$newAction->hydrate($_POST);
			$newAction->Execute();
			break;
		case 'duoCategoryModification':
			$newAction = new Action_DuoCategoryModification();
			$newAction->hydrate($_POST);
			$newAction->Execute();
			break;
		case 'userModification':
			$newAction = new Action_UserModification();
			$newAction->hydrate($_POST);
			$newAction->Execute();
			break;
		case 'duoModification':
			$newAction = new Action_DuoModification();
			$newAction->hydrate($_POST);
			$newAction->Execute();
			break;
		case 'dashboard':
			$newAction = new Action_ChangeAccount();
			$newAction->setAccountId('dashboard');
			$newAction->Execute();
			break;
		case 'configuration':
			$newAction = new Action_ChangeAccount();
			$newAction->setAccountId('configuration');
			$newAction->Execute();
			break;
		case 'deleteRecord':
			$newAction = new Action_DeleteRecord();
			$newAction->hydrate($_POST);
			$newAction->Execute();
			break;
		case 'deleteRecordInvestment':
			$newAction = new Action_DeleteRecordInvestment();
			$newAction->hydrate($_POST);
			$newAction->Execute();
			break;
		case 'confirmRecord':
			$newAction = new Action_ConfirmRecord();
			$newAction->hydrate($_POST);
			$newAction->Execute();
			break;
		case 'valueInvestment':
			$newAction = new Action_AddInvestmentValue();
			$newAction->hydrate($_POST);
			$newAction->Save();
			break;
		case 'incomeInvestment':
			$newAction = new Action_AddInvestmentIncome();
			$newAction->hydrate($_POST);
			$newAction->Save();
			break;
		case 'debit_investment':
			$newAction = new Action_AddInvestmentDebit();
			$newAction->hydrate($_POST);
			$newAction->Save();
			break;*/
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