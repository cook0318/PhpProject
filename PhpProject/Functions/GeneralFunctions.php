<?php

// Non-Validating and Non-Database related functions.

session_start();

define('FUNCTIONS_PATH', dirname(__FILE__));            // path to: PhpProject/PhpProject/Functions 
define('PROJECT_PATH', dirname(FUNCTIONS_PATH));        // path to: PhpProject/PhpProject
define("COMMON_PATH", PROJECT_PATH . '/Common');        // path to: PhpProject/PhpProject/Common

define ('TEMPLATES_URL', (substr($_SERVER['REQUEST_URI'], 0, 32))); // URL path to /PhpProject/Templates

$activePage = substr($_SERVER['REQUEST_URI'], 33, -4); // gets active URI and extracts page name

foreach (glob("../Classes/*.php") as $filename)
{
    require_once($filename);
}
require_once(FUNCTIONS_PATH . "/DatabaseAccess.php");
require_once(FUNCTIONS_PATH . "/ValidationFunctions.php");

date_default_timezone_set("America/Toronto");


// Gets the HTML for the selectable Accessibility dropdown on the Create an Album page.
// Input parameter is the currently selected option, if the form ends up being invalid.
function getAccessibilityDropdown($selectedAccessibility){
    $accessibilityCodes = getAllAccessibilityCodes();
    $returnHTML = "";
    foreach($accessibilityCodes as $code){
        $selected = "";
        $name = $code->getAccessibilityCode();
        $optionText = $name == "private" ? "Private - Accessible only by you" : "Shared - Accessible by you and friends";
        if($name == $selectedAccessibility){
            $selected = "selected";
        }
        $option = "<option value='$name' $selected>$optionText</option>";
        $returnHTML .= $option;
    }
    
    return $returnHTML;
    
}

// checks if it is POST
function isPostRequest() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

// checks if it is GET
function isGetRequest() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// checks if it is LOGGED IN (Session User is set)
function isLoggedIn() {
    return isset($_SESSION['userLogged']);
}

// redirects to Login Page if not Logged In
function requireLogin() {
    if(!isLoggedIn()) {
        header('Location: ' . TEMPLATES_URL . "/Login.php");
    }
}

// checks if argument is empty
function notEmpty($value) {
    if (isset($value) && $value != "") {
        return true;         
    } else {
        return false;
    }
}

?>