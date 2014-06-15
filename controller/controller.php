<?php
include_once '../security/security_manager.php';

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
		case 'record_confirm':
			$operation = new Operation_Record_Confirm();
			break;

		case 'record_amount_modify':
			$operation = new Operation_Record_Amount_Modify();
			break;
				
		case 'account_change':
			$operation = new Operation_Account_Change();
			break;
		case 'account_modification':
			$operation = new Operation_Account_Modification();
			break;

		case 'investmentrecord_value':
			$operation = new Operation_InvestmentRecord_Value();
			break;
		case 'investmentrecord_delete':
			$operation = new Operation_InvestmentRecord_Delete();
			break;
		case 'investmentrecord_income':
			$operation = new Operation_InvestmentRecord_Income();
			break;
		case 'investmentrecord_debit':
			$operation = new Operation_InvestmentRecord_Debit();
			break;
		case 'investmentrecord_remark':
			$operation = new Operation_InvestmentRecord_Remark();
			break;
				
		case 'user_subscription':
			$operation = new Operation_User_Subscription();
			break;
		case 'user_duo':
			$operation = new Operation_User_Duo();
			break;
		case 'user_modification':
			$operation = new Operation_User_Modification();
			break;

		case 'configuration':
			$operation = new Operation_Account_Change();
			$operation->set('accountId', 'configuration');
			break;

		case 'category_modification_duo':
			$operation = new Operation_Category_Modification_Duo();
			break;
		case 'category_modification_user':
			$operation = new Operation_Category_Modification_User();
			break;
/*
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
		case 'dashboard':
			$newAction = new Action_ChangeAccount();
			$newAction->setAccountId('dashboard');
			$newAction->Execute();
			break;
		*/
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