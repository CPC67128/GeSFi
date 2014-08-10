<?php
include '../security/security_manager.php';

$translator = new Translator();

$investmentsRecordsHandler = new InvestmentsRecordsHandler();
if (isset($_GET['accounts']))
{
	$result = $investmentsRecordsHandler->GetAllRecordsForSomeInvestments("'".str_replace(',', '\',\'', $_GET['accounts'])."'");
}
else
{
	$result = $investmentsRecordsHandler->GetAllRecordsForAllInvestments();
}

// content="text/plain; charset=utf-8"
require_once ('../3rd_party/jpgraph-3.5.0b1/src/jpgraph.php');
require_once ('../3rd_party/jpgraph-3.5.0b1/src/jpgraph_line.php');
require_once ('../3rd_party/jpgraph-3.5.0b1/src/jpgraph_date.php');

// Create a data set in range (50,70) and X-positions
DEFINE('NDATAPOINTS',360);
DEFINE('SAMPLERATE',240);

// Create the new graph
$graph = new Graph(1000,700);

// Slightly larger than normal margins at the bottom to have room for
// the x-axis labels
$graph->SetMargin(40,40,30,30);

// Fix the Y-scale to go between [0,100] and use date for the x-axis
$graph->SetScale('lin');
$graph->title->Set($translator->getTranslation("Rendement global"));

// Set the angle for the labels to 90 degrees
$graph->xaxis->SetLabelAngle(0);

$graph->img->SetAntiAliasing(false);

$XArray = array();
$YArray = array();

$previousAccountId = '';
$previousName = '';

while ($row = $result->fetch())
{
	if ($row['account_id'] != $previousAccountId)
	{
		if (count($XArray) > 0)
		{
			$linePlot = new LinePlot($YArray, $XArray);
			
			$graph->Add($linePlot);
			
			$linePlot->SetLegend($previousName);
			$linePlot->SetWeight(2);
			$linePlot->SetStyle("solid");
		}

		$XArray = array();
		$YArray = array();

		$previousAccountId = $row['account_id'];
		$previousName = $row['name'];
	}

	if (isset($row['CALC_yield']))
	{
		array_push($XArray, $row['CALC_days_since_creation']);
		array_push($YArray, $row['CALC_yield']);
	}
}

if (count($XArray) > 0)
{
	$linePlot = new LinePlot($YArray, $XArray);
		
	$graph->Add($linePlot);
		
	$linePlot->SetLegend($previousName);
	$linePlot->SetWeight(2);
	$linePlot->SetStyle("solid");
}

$graph->Stroke();
?>