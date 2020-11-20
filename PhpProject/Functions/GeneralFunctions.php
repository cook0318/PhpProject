<?php

session_start();

define('FUNCTIONS_PATH', dirname(__FILE__));        // path to: PhpProject/PhpProject/Functions 
define('PROJECT_PATH', dirname(FUNCTIONS_PATH));    // path to: PhpProject/PhpProject
define("COMMON_PATH", PROJECT_PATH . '/Common');    // path to: PhpProject/PhpProject/Common

$activePage = substr($_SERVER['REQUEST_URI'], 33, -4); // gets active URI and extracts page name

?>