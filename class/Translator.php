<?php
class Translator
{
	public function getTranslation($text)
	{
		return $text;
	}

	public function getCurrencyValuePresentation($amount)
	{
		return number_format($amount,2).'&nbsp;&euro;';
	}

	public function getCurrencyPresentation()
	{
		return '&nbsp;&euro;';
	}

	public function getMonthYearPresentation($month, $year)
	{
		return $month.'-'.$year;
	}
}