<?php
class Translator
{
	public function getTranslation($text)
	{
		return $text;
	}

	public function getCurrencyValuePresentation($amount)
	{
		return number_format($amount,2, ',', ' ').'&nbsp;&euro;';
	}

	public function getCurrencyPresentation()
	{
		return '&nbsp;&euro;';
	}

	public function getMonthYearPresentation($month, $year)
	{
		return $month.'-'.$year;
	}

	public function getMonthName($month)
	{
		$abbreviation = '';
		switch ($month)
		{
			case 1: $abbreviation = 'Janvier'; break;
			case 2: $abbreviation = 'Février'; break;
			case 3: $abbreviation = 'Mars'; break;
			case 4: $abbreviation = 'Avril'; break;
			case 5: $abbreviation = 'Mai'; break;
			case 6: $abbreviation = 'Juin'; break;
			case 7: $abbreviation = 'Juillet'; break;
			case 8: $abbreviation = 'Aout'; break;
			case 9: $abbreviation = 'Septembre'; break;
			case 10: $abbreviation = 'Octobre'; break;
			case 11: $abbreviation = 'Novembre'; break;
			case 12: $abbreviation = 'Décembre'; break;
		}

		return $abbreviation;
	}

	public function getPercentagePresentation($percentage)
	{
		if (isset($percentage))
			return number_format($percentage,2).'&nbsp;%';
	}
}