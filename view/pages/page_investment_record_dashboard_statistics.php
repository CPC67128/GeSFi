<script src="../3rd_party/highcharts-5.0.14/code/highcharts.js"></script>
<script src="../3rd_party/highcharts-5.0.14/code/modules/exporting.js"></script>

<div id="container" style="min-width: 1000px; max-width: 1000px; height: 550px; margin: 0 auto"></div>

<button id="checkAll">Cocher tout</button> <button id="uncheckAll">Décocher tout</button>

<script type="text/javascript">
var chart;

chart = new Highcharts.Chart('container', {
	title: {
		text: 'Comparaison des rendements des placements'
	},
	xAxis: {
		title: { text: "Nombre de jours depuis l'ouverture" },
	},
	yAxis: {
		title: { text: 'Rendement' },
	},
	tooltip: {
		formatter: function() { return this.series.name +': '+this.y+' %'; }
	},
	series: [
		<?php
$investmentsRecordsHandler = new InvestmentsRecordsHandler();
$result = $investmentsRecordsHandler->GetAllRecordsForAllInvestmentsForGraphics();

$previousAccountId = '';
$previousName = '';
$addComma = true;

while ($row = $result->fetch())
{
	$addComma = true;
	if ($row['account_id'] != $previousAccountId)
	{
		if ($previousAccountId != '')
		{
			echo "]},";
		}

		echo "{";
		echo "name: '".$row['name']."',";
		// echo "visible: false,";
		echo "data: [";

		$previousAccountId = $row['account_id'];
		$previousName = $row['name'];
		$addComma = false;
	}

	if (isset($row['CALC_yield']))
	{
		if ($addComma)
			echo ",";
		echo "[".$row['CALC_days_since_creation'].",".$row['CALC_yield']."]";
	}
}

echo "]}";
		?>
	]
});


$('#checkAll').click(function(){
    for(i=0; i < chart.series.length; i++) {
        if(chart.series[i].selected == false){
            chart.series[i].setVisible(true, false);
        }
    }
    chart.redraw();
});

$('#uncheckAll').click(function(){
    for(i=0; i < chart.series.length; i++) {
        if(chart.series[i].selected == false){
            chart.series[i].setVisible(false, false);
        }
    }
    chart.redraw();
});

</script>