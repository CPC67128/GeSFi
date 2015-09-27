<select name="ab" onchange="if (this.selectedIndex) displayStatisticsPage(this.options[this.selectedIndex].value);">
    <option value="-1">-</option>
    <option value="statistics_window?3">Global, 3 mois glissants</option>
	<option value="statistics_window?6">Global, 6 mois glissants</option>
    <option value="statistics_window?12">Global, 12 mois glissants</option>
    <option value="statistics_private_full_year?<?= Date('Y') ?>">Privé, année <?= Date('Y') ?></option> 
    <option value="statistics_private_full_year?<?= Date('Y') - 1 ?>">Privé, année <?= Date('Y') - 1 ?></option> 
    <option value="statistics_private_full_year?<?= Date('Y') - 2 ?>">Privé, année <?= Date('Y') - 2 ?></option>
    <option value="statistics_private_full_year?<?= Date('Y') - 3 ?>">Privé, année <?= Date('Y') - 3 ?></option>
    <option value="statistics_private_full_year?<?= Date('Y') - 4 ?>">Privé, année <?= Date('Y') - 4 ?></option>
    <option value="statistics_private_full_year?<?= Date('Y') - 5 ?>">Privé, année <?= Date('Y') - 5 ?></option>
    <option value="statistics_private_window?3">Privé, 3 mois glissants</option>
    <option value="statistics_private_window?6">Privé, 6 mois glissants</option>
    <option value="statistics_private_window?12">Privé, 12 mois glissants</option>
    <option value="statistics_duo_full">Duo, revenus et dépenses</option>
    <option value="statistics_duo_payment">Duo, évolution des dépenses</option>
    <option value="statistics_duo_window?3">Duo, 3 mois glissants</option>
    <option value="statistics_duo_window?6">Duo, 6 mois glissants</option>
    <option value="statistics_duo_window?12">Duo, 12 mois glissants</option>
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
		data: {'page': page, 'data': data},
		dataType: 'html',
		success : function(data) {
			$('#statisticsData').html(data);
		}
	});
}
</script>