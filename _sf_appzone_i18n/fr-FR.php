<?php
$LNG_Window_title = 'Gestion Financière du Couple / Compatibilité de couple';

$LNG_Month = 'Mois';
$LNG_Id_field_not_filled = 'Aucun enregistrement sélectionné';
$LNG_Actor_field_not_filled = 'Aucun acteur sélectionné';
$LNG_Amount_field_not_filled = 'Aucun montant saisi';
$LNG_Amount_field_not_filled_correctly = 'Montant saisi érroné';
$LNG_Repayment = 'Remboursement';
$LNG_Periodicity_field_not_filled = 'Aucune périodicité sélectionnée';
$LNG_Periodicity_field_not_filled_correctly = 'Périodicité sélectionnée incomplête';
$LNG_Unexpected_error = 'Erreur inattendue :';

$LNG_Expense_section_title = 'Dépense depuis un compte privé';
$LNG_Done_by = 'Effectuée par';
$LNG_Or = 'ou';
$LNG_Date = 'Date';
$LNG_Amount = 'Montant';
$LNG_Currency_representation = '&nbsp;&euro;';
$LNG_Charge_level = 'Prise en charge';
$LNG_Designation = 'Désignation';
$LNG_Category_to_select = 'Catégorie:';
$LNG_Category = 'Catégorie';
$LNG_Periodicity_to_select = 'Périodicité:';
$LNG_Periodicity_unique = 'unique';
$LNG_Periodicity_monthly = 'tous les mois';
$LNG_Periodicity_monthly_for = 'pendant';
$LNG_Periodicity_monthly_month = 'mois';
$LNG_Add = 'Ajouter';
$LNG_Reset = 'Effacer';

$LNG_Repayment_section_title = 'Remboursement entre conjoint';

$LNG_Remark_section_title = 'Remarque';

$LNG_Stats_section_title = 'Suivi mensuel';
$LNG_Expensed_over_the_last_12_months = 'dépensés sur les 12 derniers mois';

// ----------------------------------- Repayment needs display -----------------------------------
$LNG_Current_status_of_repayment = 'Etat des lieux :';
$LNG_Total_expenses = 'Total dépenses';
$LNG_Total_charged = 'Prise en charge';
$LNG_Common_part = 'Part commune';
$LNG_Repayment_from = 'Remboursement de';
$LNG_Repayment_needed_from = 'Reste à rembourser de';
$LNG_Details = 'Détails';
$LNG_Need = 'doit';
$LNG_To = 'à';
$LNG_Delete_confirmation_question = 'Etes-vous sûr de vouloir supprimer cette entrée ?';

function PrintCurrencyForDisplay($amount)
{
	printf("%6.2f", $amount);
	echo '&nbsp;&euro;';
}

function PrintMonthYearForDisplay($month, $year)
{
	echo $month.'-'.$year;
}
