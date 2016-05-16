<?php
include 'menu.php';

if ($area == 'administration')
{
	AddMenuLeftItem("administration_connection");
	AddMenuLeftItem("administration_accounts");
	AddMenuLeftItem("administration_category");
	AddMenuLeftItem("administration_designation");
	AddMenuLeftItem("administration_user");
}
else if ($area == 'investment' && $id == '')
{
	AddMenuLeftItem("investment_record_dashboard");
	AddMenuLeftItem("investment_record_dashboard_statistics");
}
else if ($area == 'investment')
{
	$account = $accountsHandler->GetCurrentActiveAccount();

	AddMenuLeftItem("investment_record");
	AddMenuLeftItem("investment_record_value");
	AddMenuLeftItem("investment_record_deposit");
	AddMenuLeftItem("investment_record_withdrawal");
	if ($account->getIfSetOrDefault('generateIncome', false))
		AddMenuLeftItem("investment_record_income");
	AddMenuLeftItem("investment_record_remark");
	AddMenuLeftItem("investment_record_statistics");
}
else
{
	AddMenuLeftItem("record");
	AddMenuLeftItem("record_payment");
	AddMenuLeftItem("record_deposit");
	AddMenuLeftItem("record_transfer");
	AddMenuLeftItem("record_remark");
	AddMenuLeftItem("record_balance");
	AddMenuLeftItem("statistics");
}
?>
<i>BudgetFox
<br />
v. 16.01.03</i>
<br />
<a href="../view/copyright.htm">Copyright</a>
<br />
<a href="#" onClick="LogoutUser(); return false;">Déconnection</a>

<script type="text/javascript">
$(".menuIcon").click(function() {
	ChangeContext_Page($(this).attr("id"));
});
</script>