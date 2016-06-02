<h1><?= $translator->getTranslation("Statistiques de l'investissement") ?></h1>

<?php
/* References:
 * http://stackoverflow.com/questions/1814182/get-the-results-of-an-include-in-a-string-in-php
 * http://stackoverflow.com/questions/3463598/using-php-function-include-to-include-a-png-image
 * http://www.commentcamarche.net/forum/affich-11858311-jpgraph
 * http://jpgraph.net/download/manuals/chunkhtml/ch05s05.html
 */

try
{
	ob_start();
	include 'page_investment_record_statistics_graph_yield.php';
	$output = ob_get_clean();

	if (empty($output))
		throw new Exception();
	?>
	<img src='data:image/jpg;base64,<?php echo base64_encode($output); ?>'>
	<?php
}
catch(Exception $exception)
{
	//echo $translator->getTranslation("Il n'est pas possible de générer un graphique par manque de données");
	print_r($exception);
}
?><br /><br /><?php
try
{
	ob_start();
	include 'page_investment_record_statistics_graph_yield_average.php';
	$output = ob_get_clean();

	if (empty($output))
		throw new Exception();
	?>
	<img src='data:image/jpg;base64,<?php echo base64_encode($output); ?>'>
	<?php
}
catch (Exception $exceptionAverage)
{
	echo $translator->getTranslation("Il n'est pas possible de générer un graphique par manque de données");
	// print_r($exceptionAverage);
}
