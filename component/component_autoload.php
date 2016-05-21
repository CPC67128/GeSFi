<?php

function __autoload($class_name)
{
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