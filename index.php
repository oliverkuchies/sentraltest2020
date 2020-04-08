<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "bootstrap/Bootstrapper.php";

//Our bootstrapper will do all the hard yards for us, and prepare all we need to get the app working!
$bootstrap = new \bootstrap\Bootstrapper();
$bootstrap->load();



