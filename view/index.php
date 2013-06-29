<?php
include '../configuration/configuration.php';
require_once('../3rd_party/mobile_device_detect/mobile_device_detect.php');
$mobile = mobile_device_detect();

$qry = $_SERVER['QUERY_STRING'];
if($mobile == true)
{
	header("location:index_pc.php?$qry");
}
else
{
	header("location:index_pc.php?$qry");
}