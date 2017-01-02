<html>
<body>
<?php
echo 'START DEBUG<br />';

include '../configuration/configuration.php';

$dns = 'mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME;
echo 'DNS = '.$dns.'<br />';
$utilisateur = $DB_USER;
echo 'User = '.$utilisateur.'<br />';
$motDePasse = $DB_PASSWORD;
echo 'Mdp = '.$motDePasse.'<br />';

try
{
	$this->_connection = new PDO( $dns, $utilisateur, $motDePasse );
	echo '.<br />';
	$this->_connection->exec("SET CHARACTER SET utf8");
	echo '.<br />';
	$this->_dbTablePrefix = $DB_TABLE_PREFIX;
	echo '.<br />';
}
catch ( Exception $e )
{
	echo "Connection à MySQL impossible : ", $e->getMessage();
}

echo 'Connected<br />';


?>
</body>
</html>