<select name="ab" onchange="if (this.selectedIndex) displayStatisticsPage(this.options[this.selectedIndex].value);">
    <option value="-1">--</option>
    <option value="statistics_private_full_year?<?= Date('Y') ?>">Tableau de bord privé, année <?= Date('Y') ?></option> 
    <option value="statistics_private_full_year?<?= Date('Y') - 1 ?>">Tableau de bord privé, année <?= Date('Y') - 1 ?></option> 
    <option value="statistics_private_full_year?<?= Date('Y') - 2 ?>">Tableau de bord privé, année <?= Date('Y') - 2 ?></option>
    <option value="statistics_private_full_year?<?= Date('Y') - 3 ?>">Tableau de bord privé, année <?= Date('Y') - 3 ?></option>
    <option value="statistics_private_full_year?<?= Date('Y') - 4 ?>">Tableau de bord privé, année <?= Date('Y') - 4 ?></option>
    <option value="statistics_private_full_year?<?= Date('Y') - 5 ?>">Tableau de bord privé, année <?= Date('Y') - 5 ?></option>
    <option value="statistics_private_window">Tableau de bord privé, 12 mois glissants</option>
    <option value="statistics_private_window_6">Tableau de bord privé, 6 mois glissants</option>
    <option value="statistics_private_window_3">Tableau de bord privé, 3 mois glissants</option>
    <option value="statistics_duo_full">Tableau de bord duo, revenus et dépenses</option>
    <option value="statistics_duo_payment">Tableau de bord duo, évolution des dépenses</option>
    <option value="statistics_duo_window">Tableau de bord duo, 12 mois glissants</option>
    <option value="statistics_duo_window_6">Tableau de bord duo, 6 mois glissants</option>
    <option value="statistics_duo_window_3">Tableau de bord duo, 3 mois glissants</option>
</select>

<div id="statisticsData"></div>

<script type='text/javascript'>

function displayStatisticsPage(statisticsContext)
{
	$('#statisticsData').html('<img src="../media/loading.gif" />');

	var splits = statisticsContext.split("?");

	page = splits[0];

	data = "";
	if (splits.length > 1)
		data = splits[1]; 

	$.ajax({
	    type : 'POST',
	    url : 'page.php',
	    data: {
	        'page': page, 
	        'data': data
	    },
	    dataType: 'html',
	    success : function(data) {
	        $('#statisticsData').html(data);
	    }
	});
}
</script>

<?php
//include 'page_statistics_private.php';
//include 'page_statistics_duo.php';
?>