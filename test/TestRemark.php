<?php
include '../component/component_autoload.php';

$newRecord = new Record_Remark(
		'8d911ca2-de74-11e2-b69b-e4d53de1ede6',
		'4768b151-bd52-11e2-8d63-5c260a87ddbb',
		'2018-10-14',
		'TEST'
		);

recordsHandler = new RecordsHandler();
recordsHandler->Insert($newRecord);
