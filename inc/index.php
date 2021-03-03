<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');
ini_set('log_errors',TRUE);
ini_set('html_errors',FALSE);
date_default_timezone_set('Asia/Riyadh');
require_once 'cities_table.php';
require_once 'connection.php';
require_once 'countries_table.php';
require_once 'csprng.php';
require_once 'helpers.php';
require_once 'notifications_table.php';
require_once 'users_table.php';
require_once 'users_log_table.php';
require_once 'hospitals_table.php';
require_once 'icus_table.php';
require_once 'patients_table.php';
