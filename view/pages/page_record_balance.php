<?php
$accountsHandler = new AccountsHandler();
$accounts = $accountsHandler->GetAllDuoAccounts();
$activeAccount = $accounts[0];

$usersHandler = new UsersHandler();
$user = $usersHandler->GetCurrentUser();
$partner = $usersHandler->GetUser($user->GetPartnerId());

$statisticsHandler = new StatisticsHandler();
$translator = new Translator();
/*
for ($year = 2014; $year < 2016; $year++)
	for ($month = 1; $month <= 12; $month++)
		$balance = new StatisticsBalanceHandler($year, $month, false);
*/
$balance = new StatisticsBalanceHandler(2016, 01, true);

?>
Situation des comptes entre <?= $user->get('name') ?> et <?= $partner->get('name') ?>

<h1><?= $translator->getTranslation('Situation globale') ?></h1>
<table class="blankTable">

<thead>
<th>Désignation</th>
<th>Apports</th>
<th>Dépenses</th>
</thead>

<tr>
<td>
<table class="summaryTable">
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>Dépenses <?= $user->get('name') ?> > catégories duo</td>
</tr>
<tr>
<td>Dépenses <?= $user->get('name') ?> > catégories <?= $partner->get('name') ?></td>
</tr>
<tr>
<td>Dépenses <?= $user->get('name') ?> > catégories <?= $user->get('name') ?></td>
</tr>
<tr>
<td>Dépenses <?= $partner->get('name') ?> > catégories duo</td>
</tr>
<tr>
<td>Dépenses <?= $partner->get('name') ?> > catégories <?= $user->get('name') ?></td>
</tr>
<tr>
<td>Dépenses <?= $partner->get('name') ?> > catégories <?= $partner->get('name') ?></td>
</tr>
<tr>
<td>Versements sur comptes duo</td>
</tr>
<tr>
<td>Revenus versées sur comptes duo</td>
</tr>
<tr>
<td>Retraits depuis comptes duo</td>
</tr>
<tr>
<td>Dépenses depuis comptes duo</td>
</tr>
<tr>
<td>Remboursement de <?= $partner->get('name') ?> à <?= $user->get('name') ?></td>
</tr>
<tr>
<td>Remboursement de <?= $user->get('name') ?> à <?= $partner->get('name') ?></td>
</tr>
<tr>
<td>Revenus duo versés sur compte privé</td>
</tr>
<tr>
<td>/ versés chez <?= $user->get('name') ?></td>
</tr>
<tr>
<td>/ versés chez <?= $partner->get('name') ?></td>
</tr>
<tr>
<td><b>Total</b></td>
</tr>
<tr>
<td>Apports - Dépenses</td>
</tr>
</table>
</td>

<td>
<table class="summaryTable">
<tr>
<td align="center"><b><i>Total</i></b></td>
<td align="center"><b><i><?= $user->get('name') ?></i></b></td>
<td align="center"><b><i><?= $partner->get('name') ?></i></b></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser) ?></td>
<td>&nbsp;</td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser) ?></td>
<td>&nbsp;</td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUser) ?></td>
<td>&nbsp;</td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner) ?></td>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartner) ?></td>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner) ?></td>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalIncomeDuoAccountsMadeByUser + $balance->totalIncomeDuoAccountsMadeByPartner) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalIncomeDuoAccountsMadeByUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalIncomeDuoAccountsMadeByPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalIncomeDuoAccountsOutsidePartners) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalIncomeDuoAccountsOutsidePartnersForUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalIncomeDuoAccountsOutsidePartnersForPartner) ?></td>
</tr>
<tr>
<td align="right">- <?= $translator->getCurrencyValuePresentation($balance->totalOutcomeDuoAccountsMadeByUser + $balance->totalOutcomeDuoAccountsMadeByPartner) ?></td>
<td align="right">- <?= $translator->getCurrencyValuePresentation($balance->totalOutcomeDuoAccountsMadeByUser) ?></td>
<td align="right">- <?= $translator->getCurrencyValuePresentation($balance->totalOutcomeDuoAccountsMadeByPartner) ?></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation(-$balance->totalRepaymentFromPartnerToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalRepaymentFromPartnerToUser) ?></td>
</tr>
<tr>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalRepaymentFromUserToPartner) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation(-$balance->totalRepaymentFromUserToPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalIncomeOutsidePartnersToUser + $balance->totalIncomeOutsidePartnersToPartner) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalIncomeOutsidePartnersToUser / 2 + $balance->totalIncomeOutsidePartnersToPartner / 2) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalIncomeOutsidePartnersToUser / 2 + $balance->totalIncomeOutsidePartnersToPartner / 2) ?></td>
</tr>
<tr>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation(-$balance->totalIncomeOutsidePartnersToUser) ?></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation(-$balance->totalIncomeOutsidePartnersToPartner) ?></td>
</tr>
<tr>
<td align="right"><b><?= $translator->getCurrencyValuePresentation($balance->totalContributionOfUser + $balance->totalContributionOfPartner) ?></b></td>
<td align="right"><b><?= $translator->getCurrencyValuePresentation($balance->totalContributionOfUser) ?></b></td>
<td align="right"><b><?= $translator->getCurrencyValuePresentation($balance->totalContributionOfPartner) ?></b></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation(($balance->totalContributionOfUser - $balance->totalExpensesChargedToUser) + ($balance->totalContributionOfPartner - $balance->totalExpensesChargedToPartner)) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalContributionOfUser - $balance->totalExpensesChargedToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalContributionOfPartner - $balance->totalExpensesChargedToPartner) ?></td>
</tr>
</table>
</td>

<td>
<table class="summaryTable">
<tr>
<td align="center"><b><i>Total</i></b></td>
<td align="center"><b><i>Part <?= $user->get('name') ?></i></b></td>
<td align="center"><b><i>Part <?= $partner->get('name') ?></i></b></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUserChargedToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToUserCategoriesMadeByUserChargedToPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartner) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartnerChargedToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToUserCategoriesMadeByPartnerChargedToPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToPartner) ?></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesDuoAccounts) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesDuoAccountsChargedForUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($balance->totalExpensesDuoAccountsChargedForPartner) ?></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td align="right"><b><?= $translator->getCurrencyValuePresentation($balance->totalExpenses) ?></b></td>
<td align="right"><b><?= $translator->getCurrencyValuePresentation($balance->totalExpensesChargedToUser) ?></b></td>
<td align="right"><b><?= $translator->getCurrencyValuePresentation($balance->totalExpensesChargedToPartner) ?></b></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table>
</td>

</tr>
</table>

<?php // ========= ?>

<h1><?= $translator->getTranslation('Situation comptes duo') ?></h1>

<table class="blankTable">
<thead>
<th>Résumé</th>
<th>Comptes</th>
</thead>

<tr>

<?php $temp = $balance->totalIncomeDuoAccountsMadeByUser + $balance->totalIncomeDuoAccountsMadeByPartner + $balance->totalIncomeDuoAccountsOutsidePartners; ?>
<?php $total = $temp; ?>
<td>
<table class="summaryTable">
<tr>
<td>Total versements</td>
<td><?= $translator->getCurrencyValuePresentation($temp) ?></td>
</tr>
<?php $temp = $balance->totalOutcomeDuoAccountsMadeByUser + $balance->totalOutcomeDuoAccountsMadeByPartner; ?>
<?php $total -= $temp; ?>
<tr>
<td>Total retraits</td>
<td>- <?= $translator->getCurrencyValuePresentation($temp) ?></td>
</tr>
<?php $temp = $balance->totalExpensesDuoAccounts; ?>
<?php $total -= $temp; ?>
<tr>
<td>Total dépenses</td>
<td>- <?= $translator->getCurrencyValuePresentation($temp) ?></td>
</tr>
<tr>
<td><i>Total</i></td>
<td><i><?= $translator->getCurrencyValuePresentation($total) ?></i></td>
</tr>
</table>
</td>

<td>
<table class="summaryTable">

<?php
$balanceDuoAccounts = 0;
$accountsHandler->GetAllDuoAccounts();
foreach ($accounts as $account)
{

	if ($account->get('type') != 2 && $account->get('type') != 4)
	{
		$balance = $account->GetBalance()
		?>
	
		<tr>
		<td><?= $account->get('name') ?></td>
		<td style='text-align: right;'><?= $translator->getCurrencyValuePresentation($balance) ?></td>
		</tr>

		<?php
		$balanceDuoAccounts += $balance;
	}
}
?>

<td><i>Total</i></td>
<td><i><?= $translator->getCurrencyValuePresentation($balanceDuoAccounts) ?></i></td>
</table>

</td>
</tr>

</table>