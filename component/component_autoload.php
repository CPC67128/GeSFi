<?php
function my_autoloader($class_name) {
	$file = '../controller/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../model/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../handler/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../security/'.$class_name . '.php';
	if (!file_exists($file))
		$file = '../i18n/'.$class_name . '.php';
	include $file;
}

spl_autoload_register('my_autoloader');