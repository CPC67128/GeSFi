<?php
$currentYear = Date('Y');
include 'page_statistics_private_full_year.php';
$currentYear = Date('Y') - 1;
include 'page_statistics_private_full_year.php';
include 'page_statistics_private_window.php';