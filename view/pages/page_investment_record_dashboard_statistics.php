<div id="container" style="min-width: 900px; height: 400px; margin: 0 auto"></div>
<button id="checkAll">Cocher tout</button> <button id="uncheckAll">Décocher tout</button>
<script type="text/javascript">
var chart;
$(function () {
    chart = new Highcharts.Chart({ //$('#container').highcharts({
        chart: {
        	renderTo: "container",
            type: 'spline'
        },
        title: {
            text: 'Comparaison des rendements des placements'
        },
        xAxis: {
        },
        yAxis: {
            title: {
                text: 'Rendement'
            },
        },
        tooltip: {
            formatter: function() {
                    return this.series.name +': '+this.y+' %';
            }
        },
        
        series: [

<?php
$investmentsRecordsHandler = new InvestmentsRecordsHandler();
$result = $investmentsRecordsHandler->GetAllRecordsForAllInvestments();

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
			echo "]},\n\n";
		}

		echo "{\n";
		echo "name: '".$row['name']."', ";
		echo "visible: false, ";
		echo "data: [\n";

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

echo "]}\n\n";

?>
		]
    });
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