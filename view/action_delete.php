<?php
include_once '../security/security_manager.php';
//include_once '../dal/dal_gfc.php';

if (!isset($_POST['id']))
	die ($LNG_Id_field_not_filled);

$id = $_POST['id'];

Delete($id);