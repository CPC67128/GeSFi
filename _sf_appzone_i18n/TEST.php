<?php
$LNG_Window_title = 'TESTGFC';

$LNG_Month = 'TESTMois';
$LNG_Id_field_not_filled = 'TESTAucun enregistrement sélectionné';
$LNG_Actor_field_not_filled = 'TESTAucun acteur sélectionné';
$LNG_Amount_field_not_filled = 'TESTAucun montant saisi';
$LNG_Amount_field_not_filled_correctly = 'TESTMontant saisi érroné';
$LNG_Repayment = 'TESTRemboursement';
$LNG_Periodicity_field_not_filled = 'TESTAucune périodicité sélectionnée';
$LNG_Periodicity_field_not_filled_correctly = 'TESTPériodicité sélectionnée incomplête';
$LNG_Unexpected_error = 'TESTErreur inattendue : ';

$LNG_Expense_section_title = 'TESTDépense';
$LNG_Done_by = 'TESTEffectuée par';
$LNG_Or = 'TEST ou ';
$LNG_Date = 'TESTDate';
$LNG_Amount = 'TESTMontant ';
$LNG_Currency_representation = 'TEST &euro;';
$LNG_Charge_level = 'TESTPrise en charge ';
$LNG_Designation = 'TESTDésignation';
$LNG_Category_to_select = 'TESTCatégorie:';
$LNG_Category = 'TESTCatégorie';
$LNG_Periodicity_to_select = 'TESTPériodicité:';
$LNG_Periodicity_unique = 'TESTunique';
$LNG_Periodicity_monthly = 'TESTtous les mois';
$LNG_Periodicity_monthly_for = 'TESTpendant ';
$LNG_Periodicity_monthly_month = 'TEST mois';
$LNG_Add = 'TESTAjouter';
$LNG_Reset = 'TESTEffacer';

$LNG_Repayment_section_title = 'TESTRemboursement';

$LNG_Stats_section_title = 'TESTStatistiques ';
$LNG_Expensed_over_the_last_12_months = 'TEST dépensés sur les 12 derniers mois';

// ----------------------------------- Repayment needs display -----------------------------------
$LNG_Current_status_of_repayment = 'TESTEtat des lieux :';
$LNG_Total_expenses = 'TESTTotal dépenses';
$LNG_Total_charged = 'TESTPrise en charge';
$LNG_Common_part = 'TESTPart commune';
$LNG_Repayment_from = 'TESTRemboursement de ';
$LNG_Repayment_needed_from = 'TESTReste à rembourser de ';
$LNG_Details = 'TESTDétails';

function PrintCurrencyForDisplay($amount)
{
	echo '£&nbsp;';
	printf("%6.3f", $amount);
}

function PrintMonthYearForDisplay($month, $year)
{
	echo $month.'-'.$year;
}
