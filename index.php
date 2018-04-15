<?php

// Front controller

//  Catch errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

//  include files
$encoding = "utf-8";

$subject_preferences = array(
    "input-charset" => $encoding,
    "output-charset" => $encoding,
    "line-length" => 76,
    "line-break-chars" => "\r\n"
);

$header = "Content-type: text/html; charset=" . $encoding . " \r\n";
$header .= "From: Matcha <" . $_SERVER['SERVER_NAME'] . "> \r\n";
$header .= "MIME-Version: 1.0 \r\n";
$header .= "Content-Transfer-Encoding: 8bit \r\n";
$header .= "Date: " . date("r (T)") . " \r\n";

date_default_timezone_set('Europe/Kiev');
define('HEADER', $header);
define('ROOT', dirname(__FILE__));
require_once(ROOT . '/core/Router.php');
require_once(ROOT . '/core/Db.php');

//  DB connection

//  Call Router

$router = new Router();
$router->run();
