<?php
$statistics = new Statistics();
$translator = new Translator();

$totalPrivateExpenseByActor1 = $statistics->GetTotalPrivateExpenseByActor(1);
$totalPrivateExpenseByActor2 = $statistics->GetTotalPrivateExpenseByActor(2);
$totalExpenseJointAccount = $statistics->GetTotalExpenseJointAccount();

$totalExpenseChargedPartByActor1 = $statistics->GetTotalExpenseChargedPartByActor(1);
$totalExpenseChargedPartByActor2 = $statistics->GetTotalExpenseChargedPartByActor(2);

// Private account
$totalRepaymentByActor1 = $statistics->GetTotalRepaymentByActor(1);
$totalRepaymentByActor2 = $statistics->GetTotalRepaymentByActor(2);

$totalPrivateExpenseChargedPartByActor1 = $statistics->GetTotalExpenseFromPrivateAccountChargedPartByActor(1);
$totalPrivateExpenseChargedPartByActor2 = $statistics->GetTotalExpenseFromPrivateAccountChargedPartByActor(2);

$totalAmountGivenByActor1 = $totalPrivateExpenseByActor1 + $totalRepaymentByActor1 - $totalRepaymentByActor2;
$totalAmountGivenByActor2 = $totalPrivateExpenseByActor2 + $totalRepaymentByActor2 - $totalRepaymentByActor1;

// Joint account
$totalIncomeJointAccountByActor1 = $statistics->GetTotalIncomeJointAccountByActor(1);
$totalIncomeJointAccountByActor2 = $statistics->GetTotalIncomeJointAccountByActor(2);

$totalJointAccountExpenseByActor1 = $statistics->GetTotalJointAccountExpenseByActor(1);
$totalJointAccountExpenseByActor2 = $statistics->GetTotalJointAccountExpenseByActor(2);

$totalJointAccountExpenseChargedByActor1 = $statistics->GetTotalJointAccountExpenseChargedPartByActor(1);
$totalJointAccountExpenseChargedByActor2 = $statistics->GetTotalJointAccountExpenseChargedPartByActor(2);

$differenceIncomeChargeActor1 = $totalAmountGivenByActor1 - $totalPrivateExpenseChargedPartByActor1;
$differenceIncomeChargeActor2 = $totalAmountGivenByActor2 - $totalPrivateExpenseChargedPartByActor2;

$totalJointAccountValueGivenByActor1 = $totalIncomeJointAccountByActor1 - $totalJointAccountExpenseChargedByActor1;
$totalJointAccountValueGivenByActor2 = $totalIncomeJointAccountByActor2 - $totalJointAccountExpenseChargedByActor2;

$differenceJointAccountContribution = $totalJointAccountValueGivenByActor1 - $totalJointAccountValueGivenByActor2;

$totalUnrepayedAmountGivenByActor1 = $totalPrivateExpenseByActor1 + $totalRepaymentByActor1 - $totalRepaymentByActor2 - $totalPrivateExpenseChargedPartByActor1;

$difference = $differenceJointAccountContribution + $totalUnrepayedAmountGivenByActor1;

$totalIncomeJointAccount = $totalIncomeJointAccountByActor1 + $totalIncomeJointAccountByActor2;
$balanceJointAccount = $totalIncomeJointAccount - $totalExpenseJointAccount;
$jointAccountExpectedMinimumBalance = $activeAccount->getExpectedMinimumBalance();
$jointAccountPlannedDebit = $statistics->GetJointAccountPlannedDebit(10);

?>

<h1><?= $translator->getTranslation('Dépenses') ?></h1>

<table style="border-spacing: 0px; cellpadding:0px; border:0px;">
<tr>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; text-align:center; font-style: italic;">
<?= $translator->getTranslation('Total des dépenses') ?><br />
<?= $translator->getCurrencyValuePresentation($totalPrivateExpenseByActor1 + $totalPrivateExpenseByActor2 + $totalExpenseJointAccount) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; background:#AAAAFF; text-align:center;">
<?= $activeAccount->GetOwnerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalPrivateExpenseByActor1) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; padding: 5px; background:#FFFFAA; text-align:center;">
<?= $translator->getTranslation('Compte commun') ?><br />
<?= $translator->getCurrencyValuePresentation($totalExpenseJointAccount) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px; background:#FFAAFF; text-align:center;">
<?= $activeAccount->GetCoownerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalPrivateExpenseByActor2) ?>
</td>
</tr>
</table>

<br />

<table style="border-spacing: 0px; cellpadding:0px; border:0px;">
<tr>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; text-align:center; font-style: italic;">
<?= $translator->getTranslation('Prise en charge des dépenses') ?><br />
<?= $translator->getCurrencyValuePresentation($totalPrivateExpenseByActor1 + $totalPrivateExpenseByActor2 + $totalExpenseJointAccount) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; background:#AAAAFF; text-align:center;">
<?= $activeAccount->GetOwnerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalExpenseChargedPartByActor1) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px; background:#FFAAFF; text-align:center;">
<?= $activeAccount->GetCoownerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalExpenseChargedPartByActor2) ?>
</td>
</tr>
</table>

<br />

<h1><?= $translator->getTranslation('Etat des lieux entre partenaires') ?></h1>

<table style="border-spacing: 0px; cellpadding:0px; border:0px;">
<tr>
<td style="border-top: 1px solid; border-left: 1px solid; padding: 5px; text-align:center; font-style: italic;">
<?= $translator->getTranslation('Somme engagée par ') ?><?= $activeAccount->GetOwnerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalAmountGivenByActor1) ?>
</td>
<td style="border-top: 1px solid; padding: 5px; border-left: 1px solid; background:#FFFFAA; text-align:center;">
<?= $translator->getTranslation('Dépenses depuis compte privé') ?><br />
<?= $translator->getCurrencyValuePresentation($totalPrivateExpenseChargedPartByActor1) ?>
</td>
<td style="border-top: 1px solid; border-right: 1px solid; padding: 5px; background:#FFAAFF; text-align:center;">
<?= $translator->getTranslation('Versement à ') ?><?= $activeAccount->GetCoownerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalRepaymentByActor1) ?>
</td>
<td style="border-top: 1px solid; border-right: 1px solid; padding: 5px; background:#AAAAFF; text-align:center;">
<?= $translator->getTranslation('- Versement de ') ?><?= $activeAccount->GetCoownerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalRepaymentByActor2) ?>
</td>
</tr>
<tr>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; text-align:center; font-style: italic;">
<?= $translator->getTranslation('Somme engagée par ') ?><?= $activeAccount->GetCoownerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalAmountGivenByActor2) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; background:#FFFFAA; text-align:center;">
<?= $translator->getTranslation('Dépenses depuis compte privé') ?><br />
<?= $translator->getCurrencyValuePresentation($totalPrivateExpenseChargedPartByActor2) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px; background:#AAAAFF; text-align:center;">
<?= $translator->getTranslation('Versement à ') ?><?= $activeAccount->GetOwnerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalRepaymentByActor2) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px; background:#FFAAFF; text-align:center;">
<?= $translator->getTranslation('- Versement de ') ?><?= $activeAccount->GetOwnerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalRepaymentByActor1) ?>
</td>
</tr>
</table>

<br />

<table style="border-spacing: 0px; cellpadding:0px; border:0px;">
<tr>
<td style="border-top: 1px solid; border-left: 1px solid; padding: 5px; background:#AAAAFF; text-align:center;">
<?= $translator->getTranslation('Somme engagée') ?><br />
<?= $translator->getCurrencyValuePresentation($totalAmountGivenByActor1) ?>
</td>
<td style="border-top: 1px solid; border-right: 1px solid; padding: 5px; background:#FFFFAA; text-align:center;">
<?= $translator->getTranslation('Prise en charge des dépenses') ?><br />
<?= $translator->getCurrencyValuePresentation($totalPrivateExpenseChargedPartByActor1) ?>
</td>
<td style="border-top: 1px solid; border-right: 1px solid; padding: 5px; text-align:center; font-style: italic;">
<?= $translator->getTranslation('Différence ') ?><?= $activeAccount->GetOwnerName() ?><br />
<?= $translator->getCurrencyValuePresentation($differenceIncomeChargeActor1) ?>
</td>
</tr>
<tr>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; background:#FFAAFF; text-align:center;">
<?= $translator->getTranslation('Somme engagée') ?><br />
<?= $translator->getCurrencyValuePresentation($totalAmountGivenByActor2) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px; background:#FFFFAA; text-align:center;">
<?= $translator->getTranslation('Prise en charge des dépenses') ?><br />
<?= $translator->getCurrencyValuePresentation($totalPrivateExpenseChargedPartByActor2) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px; text-align:center; font-style: italic;">
<?= $translator->getTranslation('Différence ') ?><?= $activeAccount->GetCoownerName() ?><br />
<?= $translator->getCurrencyValuePresentation($differenceIncomeChargeActor2) ?>
</td>
</tr>
</table>

<br />
<?php
if ($differenceIncomeChargeActor1 > 0)
	echo $activeAccount->GetCoownerName().$translator->getTranslation(' doit à ').$activeAccount->GetOwnerName();
else
	echo $activeAccount->GetOwnerName().$translator->getTranslation(' doit à ').$activeAccount->GetCoownerName();
?>&nbsp;<?= $translator->getCurrencyValuePresentation(abs($differenceIncomeChargeActor1)) ?>
<br />

<h1><?= $translator->getTranslation('Etat des lieux du compte commun'); ?></h1>

<table style="border-spacing: 0px; cellpadding:0px; border:0px;">
<?php
$criticalJointAccountBalance = false;
if ($jointAccountExpectedMinimumBalance >= ($balanceJointAccount + $jointAccountPlannedDebit))
	$criticalJointAccountBalance = true;
?>
<tr>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; background:#AAFFAA; text-align:center;">
<?= $translator->getTranslation('Total crédit') ?><br />
<?= $translator->getCurrencyValuePresentation($totalIncomeJointAccount) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; padding: 5px; background:#AAAAFF; text-align:center;">
<?= $translator->getTranslation('Total crédit ') ?><?= $activeAccount->GetOwnerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalIncomeJointAccountByActor1) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; padding: 5px; background:#FFAAFF; text-align:center;">
<?= $translator->getTranslation('Total crédit ') ?><?= $activeAccount->GetCoownerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalIncomeJointAccountByActor2) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; background:#FFFFAA; text-align:center;">
<?= $translator->getTranslation('Total débit') ?><br />
<?= $translator->getCurrencyValuePresentation($totalExpenseJointAccount) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; padding: 5px; background:#AAAAFF; text-align:center;">
<?= $translator->getTranslation('Dépense charge ') ?><?= $activeAccount->GetOwnerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalJointAccountExpenseChargedByActor1) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px; background:#FFAAFF; text-align:center;">
<?= $translator->getTranslation('Dépense charge ') ?><?= $activeAccount->GetCoownerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalJointAccountExpenseChargedByActor2) ?>
</td>
</tr>
</table>

<br />

<?php
if ($differenceJointAccountContribution > 0)
	echo $activeAccount->GetOwnerName().$translator->getTranslation(' a crédité de plus par rapport à ').$activeAccount->GetCoownerName();
else
	echo $activeAccount->GetCoownerName().$translator->getTranslation(' a crédité de plus par rapport à ').$activeAccount->GetOwnerName();
?>&nbsp;<?= $translator->getCurrencyValuePresentation(abs($differenceJointAccountContribution)) ?>

<br />

<h1><?= $translator->getTranslation('Etat des lieux global du couple'); ?></h1>
<?php
if ($difference > 0)
	echo $activeAccount->GetCoownerName().$translator->getTranslation(' doit à ').$activeAccount->GetOwnerName();
else
	echo $activeAccount->GetOwnerName().$translator->getTranslation(' doit à ').$activeAccount->GetCoownerName();
?>&nbsp;<?= $translator->getCurrencyValuePresentation(abs($difference)) ?>

<h1><?= $translator->getTranslation('Appel à versement sur le compte commun') ?></h1>

<form action="/" id="form">
<?php
$expectedTotalIncome = 0;
if ($balanceJointAccount <= $jointAccountExpectedMinimumBalance)
{
	$expectedTotalIncome += $jointAccountExpectedMinimumBalance - $balanceJointAccount;
}

if ($expectedTotalIncome < 150) //getJointAccountExpectedMinimumCredit())
	$expectedTotalIncome = 150;

$expectedIncomeFromActor1 = 0;
$expectedIncomeFromActor2 = 0;

$expectedIncomeDueToEngagmentDifference = abs($difference);
if ($expectedIncomeDueToEngagmentDifference > 500)
	$expectedIncomeDueToEngagmentDifference = 500;

if ($difference)
	$expectedIncomeFromActor2 += $expectedIncomeDueToEngagmentDifference;
else
	$expectedIncomeFromActor1 += $expectedIncomeDueToEngagmentDifference;

if (($expectedIncomeFromActor1 + $expectedIncomeFromActor2) < $expectedTotalIncome)
{
	$expectedIncomeFromActor1 = (($expectedIncomeFromActor1 + $expectedIncomeFromActor2) - $expectedTotalIncome) / 2;
	$expectedIncomeFromActor2 = (($expectedIncomeFromActor1 + $expectedIncomeFromActor2) - $expectedTotalIncome) / 2;
}
?>
<ul>
<li><?= $activeAccount->GetOwnerName() ?> : <input type='text' name='actor1AskedIncome' size="6" value='<?= number_format($expectedIncomeFromActor1, 2) ?>' /><?= $translator->getCurrencyPresentation() ?>
 / <?= $translator->getTranslation('Email') ?> <input name="actor1Email" size="40" type="text" value="<?= $activeAccount->GetOwnerEmail() ?>" />
</li>
<li><?= $activeAccount->GetCoownerName() ?> : <input type='text' name='actor2AskedIncome' size="6" value='<?= number_format($expectedIncomeFromActor2, 2) ?>' /><?= $translator->getCurrencyPresentation() ?>
 / <?= $translator->getTranslation('Email') ?> <input name="actor2Email" size="40" type="text" value="<?= $activeAccount->GetCoownerEmail() ?>" />
</li>
</ul>
<input type="submit" id='submitForm' value="Envoyer" />
<div id='formResult'></div>
</form>

<script type='text/javascript'>
$("#form").submit( function () {
	document.getElementById("submitForm").disabled = true;
	$.post (
		'controller.php?action=sendIncomeRequest',
		$(this).serialize(),
		function(response, status) {
			$("#formResult").stop().show();
			if (status == 'success') {
				if (response.indexOf("<!-- ERROR -->") >= 0) {
					$("#formResult").html(response);
				}
				else {
					$("#formResult").html(response);
					//LoadRecords();
				}
			}
			else {
				$("#formResult").html(CreateUnexpectedErrorWeb("Status = " + status));
			}
			document.getElementById("submitForm").disabled = false;

			setTimeout(function() {
				$("#formResult").fadeOut("slow", function () {
					$('#formResult').empty();
				})
			}, 4000);
		}
	);
	return false;
});
</script>