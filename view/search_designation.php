<?php
include '../security/security_manager.php';

$usersHandler = new UsersHandler();
$activeUser = $usersHandler->GetCurrentUser();

$searchString = '';
if (isset($_GET['search_string']))
	$searchString = $_GET['search_string'];

$type = 2;
if (isset($_GET['type']))
	$type = $_GET['type'];

$recordsManager = new RecordsManager();
$result = $recordsManager->ListDesignation($searchString, $type);

$output = array(
	"identifier" => "id",
	"label" => "fullName",
	"items" => array()
);

while ($row = $result->fetch())
{
	$output['items'][] = $row['designation'];
}

print json_encode($output);
?>