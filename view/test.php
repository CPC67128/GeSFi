<?php
include '../component/component_autoload.php';
$x = new Record();
$x->set('recordDate', date("Y-m-d"));
echo $x->get('recordDate');
echo '<br />';
echo $x->get('recordDateMonth');
echo '<br />';
echo $x->get('recordDateYear');
echo '<br />';
echo '<br />';
