<?php
header("HTTP/1.1 301 Moved Permanently");
$qry = $_SERVER['QUERY_STRING'];
header("Location: view/index.php?$qry");