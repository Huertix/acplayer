<?php

require_once '../vendor/autoload.php';
require_once 'database.php';
require_once 'core/App.php';
require_once 'core/Controller.php';

$base_dir = dirname(__DIR__);

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);