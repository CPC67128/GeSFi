<?php
$accountsManager = new AccountsManager();
$accounts = $accountsManager->GetAllDuoAccounts();
$activeAccount = $accounts[0];

$usersHandler = new UsersHandler();
$user = $usersHandler->GetCurrentUser();
$partner = $usersHandler->GetUser($user->GetPartnerId());

$statistics = new Statistics();
$translator = new Translator();


$totalIncomeDuoAccountsByUser = $statistics->GetTotalIncomeDuoAccountsByUser($user->getUserId());
$totalIncomeDuoAccountsByPartner = $statistics->GetTotalIncomeDuoAccountsByUser($user->GetPartnerId());
$totalIncomeOutsidePartners = $statistics->GetTotalIncomeOutsidePartnersDuoAccounts();
$totalIncomeDuoAccounts = $totalIncomeDuoAccountsByUser + $totalIncomeDuoAccountsByPartner + $totalIncomeOutsidePartners;

$totalExpenseDuoAccountsChargedForUser = $statistics->GetTotalExpenseDuoAccountsChargedForUser($user->getUserId());
$totalExpenseDuoAccountsChargedForPartner = $statistics->GetTotalExpenseDuoAccountsChargedForUser($partner->getUserId());
$totalExpenseDuoAccounts = $statistics->GetTotalExpenseDuoAccounts();

$totalValueDuoAccountsGivenByUser = $totalIncomeDuoAccountsByUser - $totalExpenseDuoAccountsChargedForUser;
$totalValueDuoAccountsGivenByPartner = $totalIncomeDuoAccountsByPartner - $totalExpenseDuoAccountsChargedForPartner;
$totalValueDuoAccountsGivenDifference = $totalValueDuoAccountsGivenByUser - $totalValueDuoAccountsGivenByPartner;

?>
Situation des comptes entre <?= $user->get('name') ?> et <?= $partner->get('name') ?>

<h1><?= $translator->getTranslation('Situation comptes duo') ?></h1>
<table class="blankTable">
<thead>
<th>Versements effectifs</th>
<th>Dépenses</th>
<th>Valeurs effectives apportées</th>
</thead>
<tr>

<td>
<table class="summaryTable">
<tr>
<td>Versements <?= $user->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalIncomeDuoAccountsByUser) ?></td>
</tr>
<tr>
<td>Versements <?= $partner->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalIncomeDuoAccountsByPartner) ?></td>
</tr>
<tr>
<td>Revenus (intérêts...)</td>
<td><?= $translator->getCurrencyValuePresentation($totalIncomeOutsidePartners) ?></td>
</tr>
<tr>
<td><i>Total versements</i></td>
<td><?= $translator->getCurrencyValuePresentation($totalIncomeDuoAccounts) ?></td>
</tr>
</table>
</td>

<td>
<table class="summaryTable">
<tr>
<td><i>Total dépenses</i></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpenseDuoAccounts) ?></td>
</tr>
<tr>
<td>Prise en charge <?= $user->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpenseDuoAccountsChargedForUser) ?></td>
</tr>
<tr>
<td>Prise en charge <?= $partner->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpenseDuoAccountsChargedForPartner) ?></td>
</tr>
</table>
</td>

<td>
<table class="summaryTable">
<tr>
<td>Valeur apportée par <?= $user->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalValueDuoAccountsGivenByUser) ?></td>
</tr>
<tr>
<td>Valeur apportée par <?= $partner->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalValueDuoAccountsGivenByPartner) ?></td>
</tr>
<tr>
<td>Différence <?= $user->get('name') ?> - <?= $partner->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalValueDuoAccountsGivenByUser - $totalValueDuoAccountsGivenByPartner) ?></td>
</tr>
</table>
</td>

</tr>
</table>

<?php // ========= ?>

<table class="blankTable">
<thead>
<th>Résumé</th>
<th>Comptes</th>
</thead>

<tr>

<td>
<table class="summaryTable">
<tr>
<td>Total versements</td>
<td><?= $translator->getCurrencyValuePresentation($totalIncomeDuoAccounts) ?></td>
</tr>
<tr>
<td>Total dépenses</td>
<td><?= $translator->getCurrencyValuePresentation($totalExpenseDuoAccounts) ?></td>
</tr>
<tr>
<td><i>Différence</i></td>
<td><i><?= $translator->getCurrencyValuePresentation($totalIncomeDuoAccounts - $totalExpenseDuoAccounts) ?></i></td>
</tr>
</table>
</td>

<td>
<table class="summaryTable">

<?php
$balanceDuoAccounts = 0;
$accountsManager->GetAllDuoAccounts();
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


<?= $translator->getTranslation('Conclusion : ')?>
<?php
if ($totalValueDuoAccountsGivenDifference > 0)
	echo $user->getName()
		.$translator->getTranslation(' a crédité ')
		.$translator->getCurrencyValuePresentation(abs($totalValueDuoAccountsGivenDifference))
		.$translator->getTranslation(' de plus par rapport à ')
		.$partner->getName()
		.'. '
		.$partner->getName()
		.$translator->getTranslation(' doit donc virer ce montant sur un compte du couple.');
else if ($totalValueDuoAccountsGivenDifference < 0)
	echo $partner->getName()
		.$translator->getTranslation(' a crédité ')
		.$translator->getCurrencyValuePresentation(abs($totalValueDuoAccountsGivenDifference))
		.$translator->getTranslation(' de plus par rapport à ')
		.$user->getName()
		.'. '
		.$user->getName()
		.$translator->getTranslation(' doit donc virer ce montant sur un compte du couple.');
else
	echo $translator->getTranslation('Equilibre entre les partenaires')
?>

<br /><br />

<?php
$totalExpensePrivateAccountsForDuoCategoriesMadeByUser = $statistics->GetTotalExpensePrivateAccountsForDuoCategoriesMadeByUser($user->getUserId());
$totalExpensePrivateAccountsForDuoCategoriesMadeByPartner = $statistics->GetTotalExpensePrivateAccountsForDuoCategoriesMadeByUser($partner->getUserId());

$totalExpensePrivateAccountsForPartnerCategoriesMadeByUser = $statistics->GetTotalExpensePrivateAccountsForPartnerCategoriesMadeByUser($user->getUserId());
$totalExpensePrivateAccountsForPartnerCategoriesMadeByPartner = $statistics->GetTotalExpensePrivateAccountsForPartnerCategoriesMadeByUser($user->GetPartnerId());

$totalExpensePrivateAccountsMadeByUser = $totalExpensePrivateAccountsForDuoCategoriesMadeByUser + $totalExpensePrivateAccountsForPartnerCategoriesMadeByUser;
$totalExpensePrivateAccountsMadeByPartner = $totalExpensePrivateAccountsForDuoCategoriesMadeByPartner + $totalExpensePrivateAccountsForPartnerCategoriesMadeByPartner;


$totalExpensePrivateAccountsForDuoCategoriesChargedForUser = $statistics->GetTotalExpensePrivateAccountsForDuoCategoriesChargedForUser($user->getUserId());
$totalExpensePrivateAccountsForDuoCategoriesChargedForPartner = $statistics->GetTotalExpensePrivateAccountsForDuoCategoriesChargedForUser($partner->getUserId());

$totalExpensePrivateAccountsForPartnerCategoriesChargedForUser = $statistics->GetTotalExpensePrivateAccountsForPartnerCategoriesChargedForUser($user->getUserId());
$totalExpensePrivateAccountsForPartnerCategoriesChargedForPartner = $statistics->GetTotalExpensePrivateAccountsForPartnerCategoriesChargedForUser($user->GetPartnerId());

$totalExpensePrivateAccountsChargedForUser = $totalExpensePrivateAccountsForDuoCategoriesChargedForUser + $totalExpensePrivateAccountsForPartnerCategoriesChargedForUser;
$totalExpensePrivateAccountsChargedForPartner = $totalExpensePrivateAccountsForDuoCategoriesChargedForPartner + $totalExpensePrivateAccountsForPartnerCategoriesChargedForPartner;


$totalRepaymentNeedByUser = $totalExpensePrivateAccountsMadeByUser - $totalExpensePrivateAccountsChargedForUser;
$totalRepaymentNeedByPartner = $totalExpensePrivateAccountsMadeByPartner - $totalExpensePrivateAccountsChargedForPartner;
$totalRepaymentNeedDifference = $totalRepaymentNeedByUser - $totalRepaymentNeedByPartner;

$totalRepaymentFromUserToPartner = $statistics->GetTotalRepaymentFromUserToPartner($user->getUserId(), $user->getPartnerId());
$totalRepaymentFromPartnerToUser = $statistics->GetTotalRepaymentFromUserToPartner($user->getPartnerId(), $user->getUserId());
$totalRepaymentDifference = $totalRepaymentFromUserToPartner - $totalRepaymentFromPartnerToUser;

$totalRepaymentRequest = $totalRepaymentNeedDifference + $totalRepaymentDifference;

?>

<h1><?= $translator->getTranslation('Situation comptes privées') ?></h1>
<table class="blankTable">
<thead>
<th>Dépenses</th>
<th>Prise en charge</th>
<th>Remboursement nécéssaire</th>
<th>Remboursements</th>
<th>Remboursement nécéssaire restant</th>
</thead>
<tr>

<td>
<table class="summaryTable">
<tr>
<td>Dépenses <?= $user->get('name') ?> > catégories duo</td>
<td><?= $translator->getCurrencyValuePresentation($totalExpensePrivateAccountsForDuoCategoriesMadeByUser) ?></td>
</tr>
<tr>
<td>Dépenses <?= $user->get('name') ?> > catégories <?= $partner->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpensePrivateAccountsForPartnerCategoriesMadeByUser) ?></td>
</tr>
<tr>
<td><i>Total <?= $user->get('name') ?></i></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpensePrivateAccountsMadeByUser) ?></td>
</tr>
<tr>
<td>Dépenses <?= $partner->get('name') ?> > catégories duo</td>
<td><?= $translator->getCurrencyValuePresentation($totalExpensePrivateAccountsForDuoCategoriesMadeByPartner) ?></td>
</tr>
<tr>
<td>Dépenses <?= $partner->get('name') ?> > catégories <?= $user->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpensePrivateAccountsForPartnerCategoriesMadeByPartner) ?></td>
</tr>
<tr>
<td><i>Total <?= $partner->get('name') ?></i></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpensePrivateAccountsMadeByPartner) ?></td>
</tr>
</table>
</td>

<td>
<table class="summaryTable">
<tr>
<td>Prise en charge <?= $user->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpensePrivateAccountsForDuoCategoriesChargedForUser) ?></td>
</tr>
<tr>
<td>Prise en charge <?= $user->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpensePrivateAccountsForPartnerCategoriesChargedForUser) ?></td>
</tr>
<tr>
<td><i>Total <?= $user->get('name') ?></i></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpensePrivateAccountsChargedForUser) ?></td>
</tr>
<tr>
<td>Prise en charge <?= $partner->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpensePrivateAccountsForDuoCategoriesChargedForPartner) ?></td>
</tr>
<tr>
<td>Prise en charge <?= $partner->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpensePrivateAccountsForPartnerCategoriesChargedForPartner) ?></td>
</tr>
<tr>
<td><i>Total <?= $partner->get('name') ?></i></td>
<td><?= $translator->getCurrencyValuePresentation($totalExpensePrivateAccountsChargedForPartner) ?></td>
</tr>
</table>
</td>

<td>
<table class="summaryTable">
<tr>
<td>De <?= $partner->get('name') ?> à <?= $user->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalRepaymentNeedByUser) ?></td>
</tr>
<tr>
<td>De <?= $user->get('name') ?> à <?= $partner->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalRepaymentNeedByPartner) ?></td>
</tr>
<tr>
<td><i>Différence</i></td>
<td><?= $translator->getCurrencyValuePresentation($totalRepaymentNeedDifference) ?></td>
</tr>
</table>
</td>

<td>
<table class="summaryTable">
<tr>
<td>De <?= $partner->get('name') ?> à <?= $user->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalRepaymentFromPartnerToUser) ?></td>
</tr>
<tr>
<td>De <?= $user->get('name') ?> à <?= $partner->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalRepaymentFromUserToPartner) ?></td>
</tr>
<tr>
<td><i>Différence</i></td>
<td><?= $translator->getCurrencyValuePresentation($totalRepaymentDifference) ?></td>
</tr>
</table>
</td>

<td>
<table class="summaryTable">
<tr>
<td>De <?= $partner->get('name') ?> à <?= $user->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalRepaymentNeedByUser - $totalRepaymentFromPartnerToUser) ?></td>
</tr>
<tr>
<td>De <?= $user->get('name') ?> à <?= $partner->get('name') ?></td>
<td><?= $translator->getCurrencyValuePresentation($totalRepaymentNeedByPartner - $totalRepaymentFromUserToPartner) ?></td>
</tr>
<tr>
<td><i>Différence</i></td>
<td><?= $translator->getCurrencyValuePresentation(($totalRepaymentNeedByUser - $totalRepaymentFromPartnerToUser) - ($totalRepaymentNeedByPartner - $totalRepaymentFromUserToPartner)) ?></td>
</tr>
</table>
</td>

</tr>
</table>

<?= $translator->getTranslation('Conclusion : ')?>
<?php
if ($totalRepaymentRequest < 0)
	echo $user->getName()
		.$translator->getTranslation(' doit encore rembourser ')
		.$translator->getCurrencyValuePresentation(abs($totalRepaymentRequest))
		.$translator->getTranslation(' à ')
		.$partner->getName()
		.'. ';
else if ($totalRepaymentRequest > 0)
	echo $partner->getName()
		.$translator->getTranslation(' doit encore rembourser ')
		.$translator->getCurrencyValuePresentation(abs($totalRepaymentRequest))
		.$translator->getTranslation(' à ')
		.$user->getName()
		.'. ';
else
	echo $translator->getTranslation('Equilibre entre les partenaires')
?>

<h1><?= $translator->getTranslation('Conclusion globale') ?></h1>

<?php

$globalRepaymentRequest = $totalValueDuoAccountsGivenDifference + $totalRepaymentRequest;

?>
<?php
if ($globalRepaymentRequest < 0)
	echo $user->getName()
		.$translator->getTranslation(' doit verser ')
		.$translator->getCurrencyValuePresentation(abs($globalRepaymentRequest))
		.$translator->getTranslation(' sur un compte commun ou rembourser ')
		.$partner->getName()
		.$translator->getTranslation(' directement de cette somme.');
else if ($globalRepaymentRequest > 0)
	echo $partner->getName()
		.$translator->getTranslation(' doit verser ')
		.$translator->getCurrencyValuePresentation(abs($globalRepaymentRequest))
		.$translator->getTranslation(' sur un compte commun ou rembourser ')
		.$user->getName()
		.$translator->getTranslation(' directement de cette somme.');
else
	echo $translator->getTranslation('Equilibre entre les partenaires')
?>