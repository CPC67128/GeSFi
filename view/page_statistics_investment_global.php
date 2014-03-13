<div id="container" style="min-width: 900px; height: 400px; margin: 0 auto"></div>

<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
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
$investmentsRecordsManager = new InvestmentsRecordsManager();
$result = $investmentsRecordsManager->GetAllRecordsForAllInvestments();

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
		echo "name: '".$row['name']."',\n";
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


</script>