<?php
date_default_timezone_set('Africa/Kinshasa');
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/src/Autoloader.php';
Autoloader::register();
require_once __DIR__ . '/config/constans.php';