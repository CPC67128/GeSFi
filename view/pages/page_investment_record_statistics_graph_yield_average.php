<?php
$investmentsRecordsHandler = new InvestmentsRecordsHandler();
$result = $investmentsRecordsHandler->GetAllRecords();

$yieldXArray = array();
$yieldYArray = array();

$yieldAverageXArray = array();
$yieldAverageYArray = array();

while ($row = $result->fetch())
{
	$paymentAccumulated = $row['CALC_amount_accumulated'];
	$paymentInvestedAccumulated = $row['CALC_amount_invested_accumulated'];

	if (isset($row['CALC_yield']))
	{
		array_push($yieldXArray, strtotime($row['record_date']));
		array_push($yieldYArray, $row['CALC_yield']);
	}

	if (isset($row['CALC_yield_average']))
	{
		array_push($yieldAverageXArray, strtotime($row['record_date']));
		array_push($yieldAverageYArray, $row['CALC_yield_average']);
	}
}

// content="text/plain; charset=utf-8"
require_once ('../3rd_party/jpgraph-3.5.0b1/src/jpgraph.php');
require_once ('../3rd_party/jpgraph-3.5.0b1/src/jpgraph_line.php');
require_once ('../3rd_party/jpgraph-3.5.0b1/src/jpgraph_date.php');

// Create a data set in range (50,70) and X-positions
/*
if (!defined('NDATAPOINTS'))
	DEFINE('NDATAPOINTS',360);
if (!defined('SAMPLERATE'))
	DEFINE('SAMPLERATE',240);
*/

// Create the new graph
$graph = new Graph(1000,300);

// Slightly larger than normal margins at the bottom to have room for
// the x-axis labels
$graph->SetMargin(40,40,30,30);

// Fix the Y-scale to go between [0,100] and use date for the x-axis
$graph->SetScale('datlin');
$graph->title->Set($translator->getTranslation("Rendement annuel"));

// Set the angle for the labels to 90 degrees
$graph->xaxis->SetLabelAngle(0);

$graph->img->SetAntiAliasing(false);

$lineYieldAverage = new LinePlot($yieldAverageYArray, $yieldAverageXArray);

$graph->Add($lineYieldAverage);

$lineYieldAverage->SetColor('lightred');
$lineYieldAverage->SetFillColor('lightred@0.8');
$lineYieldAverage->SetWeight(2);
$lineYieldAverage->SetStyle("solid");

// $graph->Stroke(); sends the headers
$graph->Stroke(_IMG_HANDLER);

$graph->img->Stream();