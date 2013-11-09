<?php
include_once '../security/security_manager.php';

function __autoload($class_name)
{
	include '../class/'.$class_name . '.php';
}

try
{
	switch ($_GET['action'])
	{
		case 'expense':
			$newExpense = new Operation_Money_Expense();
			$newExpense->hydrate($_POST);
			$newExpense->Save();
			break;
		case 'income':
			$newIncome = new Operation_Money_Income();
			$newIncome->hydrate($_POST);
			$newIncome->Save();
			break;
		case 'transfer':
			$newAction = new Operation_Money_Transfer();
			$newAction->hydrate($_POST);
			$newAction->Save();
			break;
		case 'remark':
			$newRemark = new Operation_Remark();
			$newRemark->hydrate($_POST);
			$newRemark->Save();
			break;
		case 'remarkInvestment':
			$newRemark = new Operation_Remark_Investment();
			$newRemark->hydrate($_POST);
			$newRemark->Save();
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
		case 'changeAccount':
			$newAction = new Action_ChangeAccount();
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
	}
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