<?php
$accountsHandler = new AccountsHandler();
$accounts = $accountsHandler->GetAllDuoAccounts();
$activeAccount = $accounts[0];

$usersHandler = new UsersHandler();
$user = $usersHandler->GetCurrentUser();
$partner = $usersHandler->GetUser($user->GetPartnerId());

$statisticsHandler = new StatisticsHandler();
$translator = new Translator();

$totalContributionOfUser = 0;
$totalContributionOfPartner = 0;
$totalExpenses = 0;
$totalExpensesChargedToUser = 0;
$totalExpensesChargedToPartner = 0;


$totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser = $statisticsHandler->GetTotalExpensePrivateAccountsForDuoCategoriesMadeByUser($user->get('userId'));
$totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToUser = $statisticsHandler->GetTotalExpensePrivateAccountsForDuoCategoriesChargedForUser($user->get('userId'));
$totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToPartner = $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser - $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToUser;

$totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser = $statisticsHandler->GetTotalExpensePrivateAccountsForPartnerCategoriesMadeByUser($user->get('userId'));
$totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToUser = $statisticsHandler->GetTotalExpensePrivateAccountsForPartnerCategoriesChargedForUser($user->get('userId'));
$totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToPartner = $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser - $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToUser;

$totalContributionOfUser += $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser + $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser;
$totalExpenses += $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser + $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser;
$totalExpensesChargedToUser += $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToUser + $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToUser;
$totalExpensesChargedToPartner += $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToPartner + $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToPartner;


$totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner = $statisticsHandler->GetTotalExpensePrivateAccountsForDuoCategoriesMadeByUser($partner->get('userId'));
$totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToPartner = $statisticsHandler->GetTotalExpensePrivateAccountsForDuoCategoriesChargedForUser($partner->get('userId'));
$totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToUser = $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner - $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToPartner;

$totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner = $statisticsHandler->GetTotalExpensePrivateAccountsForPartnerCategoriesMadeByUser($partner->get('userId'));
$totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToPartner = $statisticsHandler->GetTotalExpensePrivateAccountsForPartnerCategoriesChargedForUser($partner->get('userId'));
$totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToUser = $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner - $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToPartner;

$totalContributionOfPartner += $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner + $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner;
$totalExpenses += $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner + $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner;
$totalExpensesChargedToUser += $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToUser + $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToUser;
$totalExpensesChargedToPartner += $totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToPartner + $totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToPartner;


$totalIncomeDuoAccountsMadeByUser = $statisticsHandler->GetTotalIncomeDuoAccountsByUser($user->get('userId'));
$totalIncomeDuoAccountsMadeByPartner = $statisticsHandler->GetTotalIncomeDuoAccountsByUser($user->GetPartnerId());
$totalIncomeDuoAccountsOutsidePartners = $statisticsHandler->GetTotalDepositFromOutsideToDuoAccounts();

$totalOutcomeDuoAccountsMadeByUser = $statisticsHandler->GetTotalOutcomeFromDuoAccountsByUser($user->get('userId'));
$totalOutcomeDuoAccountsMadeByPartner = $statisticsHandler->GetTotalOutcomeFromDuoAccountsByUser($user->GetPartnerId());

$totalExpensesDuoAccounts = $statisticsHandler->GetTotalExpenseDuoAccounts();
$totalExpensesDuoAccountsChargedForUser = $statisticsHandler->GetTotalExpenseDuoAccountsChargedForUser($user->get('userId'));
$totalExpensesDuoAccountsChargedForPartner = $statisticsHandler->GetTotalExpenseDuoAccountsChargedForUser($user->GetPartnerId());

$totalContributionOfUser += $totalIncomeDuoAccountsMadeByUser - $totalOutcomeDuoAccountsMadeByUser + $totalIncomeDuoAccountsOutsidePartners / 2;
$totalContributionOfPartner += $totalIncomeDuoAccountsMadeByPartner - $totalOutcomeDuoAccountsMadeByPartner + $totalIncomeDuoAccountsOutsidePartners / 2;
$totalExpenses += $totalExpensesDuoAccounts;
$totalExpensesChargedToUser += $totalExpensesDuoAccountsChargedForUser;
$totalExpensesChargedToPartner += $totalExpensesDuoAccountsChargedForPartner;


$totalRepaymentFromUserToPartner = $statisticsHandler->GetTotalRepaymentFromUserToPartner($user->get('userId'), $user->getPartnerId());
$totalRepaymentFromPartnerToUser = $statisticsHandler->GetTotalRepaymentFromUserToPartner($user->getPartnerId(), $user->get('userId'));

$totalContributionOfUser += $totalRepaymentFromUserToPartner - $totalRepaymentFromPartnerToUser;
$totalContributionOfPartner += $totalRepaymentFromPartnerToUser - $totalRepaymentFromUserToPartner;


$totalIncomeOutsidePartnersToUser = $statisticsHandler->GetTotalDepositFromOutsideToPrivateAccounts($user->get('userId'));
$totalIncomeOutsidePartnersToPartner = $statisticsHandler->GetTotalDepositFromOutsideToPrivateAccounts($user->GetPartnerId());

$totalContributionOfUser += $totalIncomeOutsidePartnersToPartner / 2 - $totalIncomeOutsidePartnersToUser / 2; 
$totalContributionOfPartner += $totalIncomeOutsidePartnersToUser / 2 - $totalIncomeOutsidePartnersToPartner / 2;

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
<td>Dépenses <?= $partner->get('name') ?> > catégories duo</td>
</tr>
<tr>
<td>Dépenses <?= $partner->get('name') ?> > catégories <?= $user->get('name') ?></td>
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
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser) ?></td>
<td>&nbsp;</td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser) ?></td>
<td>&nbsp;</td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner) ?></td>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner) ?></td>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalIncomeDuoAccountsMadeByUser + $totalIncomeDuoAccountsMadeByPartner) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalIncomeDuoAccountsMadeByUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalIncomeDuoAccountsMadeByPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalIncomeDuoAccountsOutsidePartners) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalIncomeDuoAccountsOutsidePartners / 2) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalIncomeDuoAccountsOutsidePartners / 2) ?></td>
</tr>
<tr>
<td align="right">- <?= $translator->getCurrencyValuePresentation($totalOutcomeDuoAccountsMadeByUser + $totalOutcomeDuoAccountsMadeByPartner) ?></td>
<td align="right">- <?= $translator->getCurrencyValuePresentation($totalOutcomeDuoAccountsMadeByUser) ?></td>
<td align="right">- <?= $translator->getCurrencyValuePresentation($totalOutcomeDuoAccountsMadeByPartner) ?></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation(-$totalRepaymentFromPartnerToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalRepaymentFromPartnerToUser) ?></td>
</tr>
<tr>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalRepaymentFromUserToPartner) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation(-$totalRepaymentFromUserToPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalIncomeOutsidePartnersToUser + $totalIncomeOutsidePartnersToPartner) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalIncomeOutsidePartnersToUser / 2 + $totalIncomeOutsidePartnersToPartner / 2) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalIncomeOutsidePartnersToUser / 2 + $totalIncomeOutsidePartnersToPartner / 2) ?></td>
</tr>
<tr>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation(-$totalIncomeOutsidePartnersToUser) ?></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td align="right"><?= $translator->getCurrencyValuePresentation(-$totalIncomeOutsidePartnersToPartner) ?></td>
</tr>
<tr>
<td align="right"><b><?= $translator->getCurrencyValuePresentation($totalContributionOfUser + $totalContributionOfPartner) ?></b></td>
<td align="right"><b><?= $translator->getCurrencyValuePresentation($totalContributionOfUser) ?></b></td>
<td align="right"><b><?= $translator->getCurrencyValuePresentation($totalContributionOfPartner) ?></b></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation(($totalContributionOfUser - $totalExpensesChargedToUser) + ($totalContributionOfPartner - $totalExpensesChargedToPartner)) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalContributionOfUser - $totalExpensesChargedToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalContributionOfPartner - $totalExpensesChargedToPartner) ?></td>
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
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToDuoCategoriesMadeByUserChargedToPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByUserChargedToPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartner) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToDuoCategoriesMadeByPartnerChargedToPartner) ?></td>
</tr>
<tr>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartner) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesFromPrivateAccountsToPartnerCategoriesMadeByPartnerChargedToPartner) ?></td>
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
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesDuoAccounts) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesDuoAccountsChargedForUser) ?></td>
<td align="right"><?= $translator->getCurrencyValuePresentation($totalExpensesDuoAccountsChargedForPartner) ?></td>
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
<td align="right"><b><?= $translator->getCurrencyValuePresentation($totalExpenses) ?></b></td>
<td align="right"><b><?= $translator->getCurrencyValuePresentation($totalExpensesChargedToUser) ?></b></td>
<td align="right"><b><?= $translator->getCurrencyValuePresentation($totalExpensesChargedToPartner) ?></b></td>
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

<?php $temp = $totalIncomeDuoAccountsMadeByUser + $totalIncomeDuoAccountsMadeByPartner + $totalIncomeDuoAccountsOutsidePartners; ?>
<?php $total = $temp; ?>
<td>
<table class="summaryTable">
<tr>
<td>Total versements</td>
<td><?= $translator->getCurrencyValuePresentation($temp) ?></td>
</tr>
<?php $temp = $totalOutcomeDuoAccountsMadeByUser + $totalOutcomeDuoAccountsMadeByPartner; ?>
<?php $total -= $temp; ?>
<tr>
<td>Total retraits</td>
<td>- <?= $translator->getCurrencyValuePresentation($temp) ?></td>
</tr>
<?php $temp = $totalExpensesDuoAccounts; ?>
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
		$balance = $account->GetBalance();
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