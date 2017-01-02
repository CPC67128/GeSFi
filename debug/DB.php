<html>
<body>
<?php
echo 'START DEBUG<br />';

include '../configuration/configuration.php';

$dns = 'mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME;
echo 'DNS = '.$dns.'<br />';
$utilisateur = $DB_USER;
$motDePasse = $DB_PASSWORD;
$this->_connection = new PDO( $dns, $utilisateur, $motDePasse );
$this->_connection->exec("SET CHARACTER SET utf8");
$this->_dbTablePrefix = $DB_TABLE_PREFIX;

$this->_isReadOnly = $READ_ONLY;

echo 'Connected<br />';


?>
</body>
</html>