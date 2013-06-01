<?php
$statistics = new Statistics();
$translator = new Translator();

?>
<h1><?= $translator->getTranslation('Etat des lieux entre partenaires') ?></h1>
<table style="border-spacing: 0px; cellpadding:0px; border:0px;">
<?php
$totalPrivateExpenseByActor1 = $statistics->GetTotalPrivateExpenseByActor(1);
$totalPrivateExpenseByActor2 = $statistics->GetTotalPrivateExpenseByActor(2);
$totalExpenseJointAccount = $statistics->GetTotalExpenseJointAccount();
?>
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
<?php
$totalExpenseChargedPartByActor1 = $statistics->GetTotalExpenseChargedPartByActor(1);
$totalExpenseChargedPartByActor2 = $statistics->GetTotalExpenseChargedPartByActor(2);
?>
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

<table style="border-spacing: 0px; cellpadding:0px; border:0px;">
<?php
$totalIncomeJointAccountByActor1 = $statistics->GetTotalIncomeJointAccountByActor(1);
$totalIncomeJointAccountByActor2 = $statistics->GetTotalIncomeJointAccountByActor(2);
$totalRepaymentByActor1 = $statistics->GetTotalRepaymentByActor(1);
$totalRepaymentByActor2 = $statistics->GetTotalRepaymentByActor(2);
$totalAmountGivenByActor1 = $totalIncomeJointAccountByActor1 + $totalPrivateExpenseByActor1 + $totalRepaymentByActor1 - $totalRepaymentByActor2; 
$totalAmountGivenByActor2 = $totalIncomeJointAccountByActor2 + $totalPrivateExpenseByActor2 + $totalRepaymentByActor2 - $totalRepaymentByActor1;
?>
<tr>
<td style="border-top: 1px solid; border-left: 1px solid; padding: 5px; text-align:center; font-style: italic;">
<?= $translator->getTranslation('Somme engagée par ') ?><?= $activeAccount->GetOwnerName() ?><br />
<?= $translator->getCurrencyValuePresentation($totalAmountGivenByActor1) ?>
</td>
<td style="border-top: 1px solid; border-left: 1px solid; padding: 5px; background:#FF5555; text-align:center;">
<?= $translator->getTranslation('Crédit compte commun') ?><br />
<?= $translator->getCurrencyValuePresentation($totalIncomeJointAccountByActor1) ?>
</td>
<td style="border-top: 1px solid; padding: 5px; background:#FFFFAA; text-align:center;">
<?= $translator->getTranslation('Dépenses depuis compte privé') ?><br />
<?= $translator->getCurrencyValuePresentation($totalPrivateExpenseByActor1) ?>
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
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; background:#FF5555; text-align:center;">
<?= $translator->getTranslation('Crédit compte commun') ?><br />
<?= $translator->getCurrencyValuePresentation($totalIncomeJointAccountByActor2) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; padding: 5px; background:#FFFFAA; text-align:center;">
<?= $translator->getTranslation('Dépenses depuis compte privé') ?><br />
<?= $translator->getCurrencyValuePresentation($totalPrivateExpenseByActor2) ?>
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
<?php
$differenceIncomeChargeActor1 = $totalAmountGivenByActor1 - $totalExpenseChargedPartByActor1;
$differenceIncomeChargeActor2 = $totalAmountGivenByActor2 - $totalExpenseChargedPartByActor2;
?>
<tr>
<td style="border-top: 1px solid; border-left: 1px solid; padding: 5px; text-align:center; font-style: italic;">
<?= $translator->getTranslation('Trop-versé ') ?><?= $activeAccount->GetOwnerName() ?><br />
<?= $translator->getCurrencyValuePresentation($differenceIncomeChargeActor1) ?>
</td>
<td style="border-top: 1px solid; border-left: 1px solid; padding: 5px; background:#AAAAFF; text-align:center;">
<?= $translator->getTranslation('Somme engagée') ?><br />
<?= $translator->getCurrencyValuePresentation($totalAmountGivenByActor1) ?>
</td>
<td style="border-top: 1px solid; border-right: 1px solid; padding: 5px; background:#FFFFAA; text-align:center;">
<?= $translator->getTranslation('Prise en charge des dépenses') ?><br />
<?= $translator->getCurrencyValuePresentation($totalExpenseChargedPartByActor1) ?>
</td>
</tr>
<tr>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; text-align:center; font-style: italic;">
<?= $translator->getTranslation('Trop-versé ') ?><?= $activeAccount->GetCoownerName() ?><br />
<?= $translator->getCurrencyValuePresentation($differenceIncomeChargeActor2) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; background:#FFAAFF; text-align:center;">
<?= $translator->getTranslation('Somme engagée') ?><br />
<?= $translator->getCurrencyValuePresentation($totalAmountGivenByActor2) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px; background:#FFFFAA; text-align:center;">
<?= $translator->getTranslation('Prise en charge des dépenses') ?><br />
<?= $translator->getCurrencyValuePresentation($totalExpenseChargedPartByActor2) ?>
</td>
</tr>
</table>

<br />

<table style="border-spacing: 0px; cellpadding:0px; border:0px;">
<?php
$difference = $differenceIncomeChargeActor1 - $differenceIncomeChargeActor2;
?>
<tr>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; text-align:center; font-style: italic;">
<?php
if ($difference > 0)
	echo $activeAccount->GetOwnerName().$translator->getTranslation(' a engagé en plus par rapport à ').$activeAccount->GetCoownerName();
else
	echo $activeAccount->GetCoownerName().$translator->getTranslation(' a engagé en plus par rapport à ').$activeAccount->GetOwnerName();
?>&nbsp;<?= $translator->getCurrencyValuePresentation(abs($difference)) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; background:#AAAAFF; text-align:center;">
<?= $translator->getTranslation('Trop-versé ') ?><?= $activeAccount->GetOwnerName() ?><br />
<?= $translator->getCurrencyValuePresentation($differenceIncomeChargeActor1) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px; background:#FFAAFF; text-align:center;">
<?= $translator->getTranslation('Trop-versé ') ?><?= $activeAccount->GetCoownerName() ?><br />
<?= $translator->getCurrencyValuePresentation($differenceIncomeChargeActor2) ?>
</td>
</tr>
</table>

<h1><?= $translator->getTranslation('Etat des lieux du compte commun'); ?></h1>

<table style="border-spacing: 0px; cellpadding:0px; border:0px;">
<?php
$totalIncomeJointAccount = $totalIncomeJointAccountByActor1 + $totalIncomeJointAccountByActor2;
$balanceJointAccount = $totalIncomeJointAccount - $totalExpenseJointAccount;
$jointAccountExpectedMinimumBalance = $activeAccount->getExpectedMinimumBalance();
$jointAccountPlannedDebit = $statistics->GetJointAccountPlannedDebit(10);

$criticalJointAccountBalance = false;
if ($jointAccountExpectedMinimumBalance >= ($balanceJointAccount + $jointAccountPlannedDebit))
	$criticalJointAccountBalance = true;
?>
<tr>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; text-align:center; font-style: italic;">
<?= $criticalJointAccountBalance ? '<font color="red"><b>' : '' ?>
<?= $translator->getTranslation('Sole du compte commun réel') ?><br />
<?= $translator->getCurrencyValuePresentation($balanceJointAccount) ?>
<?= $criticalJointAccountBalance ? '</b></font>' : '' ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px solid; padding: 5px; background:#AAAAFF; text-align:center;">
<?= $translator->getTranslation('Total crédit') ?><br />
<?= $translator->getCurrencyValuePresentation($totalIncomeJointAccount) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px; background:#FFAAFF; text-align:center;">
<?= $translator->getTranslation('Total débit') ?><br />
<?= $translator->getCurrencyValuePresentation($totalExpenseJointAccount) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px; text-align:center;">
<?= $translator->getTranslation('Solde minimum requis') ?><br />
<?= $translator->getCurrencyValuePresentation($jointAccountExpectedMinimumBalance) ?>
</td>
<td style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; padding: 5px; text-align:center;">
<?= $translator->getTranslation('Dépenses prévues (10 jours)') ?><br />
<?= $translator->getCurrencyValuePresentation($jointAccountPlannedDebit) ?>
</td>
</tr>
</table>

<h1><?= $translator->getTranslation('Appel à versement sur le compte commun') ?></h1>
<form action="/" id="form">
<?php
$expectedTotalIncome = 0;
if ($balanceJointAccount <= ($jointAccountExpectedMinimumBalance + $jointAccountExpectedMinimumBalance))
{
	$expectedTotalIncome += ($jointAccountExpectedMinimumBalance + $jointAccountExpectedMinimumBalance) - $balanceJointAccount;
}

if ($expectedTotalIncome < 150) //getJointAccountExpectedMinimumCredit())
	$expectedTotalIncome = 150;

$expectedIncomeFromActor1 = $expectedTotalIncome * (50 / 100);
$expectedIncomeFromActor2 = $expectedTotalIncome * (50 / 100);

$expectedIncomeDueToEngagmentDifference = abs($difference);
if ($expectedIncomeDueToEngagmentDifference > 300)
	$expectedIncomeDueToEngagmentDifference = 300;

if ($difference) // = $differenceIncomeChargeActor1 - $differenceIncomeChargeActor2
	$expectedIncomeFromActor2 += $expectedIncomeDueToEngagmentDifference;
else
	$expectedIncomeFromActor1 += $expectedIncomeDueToEngagmentDifference;
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