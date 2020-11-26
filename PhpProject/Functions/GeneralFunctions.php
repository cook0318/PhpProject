<?php

// Non-Validating and Non-Database related functions.

session_start();

define('FUNCTIONS_PATH', dirname(__FILE__));        // path to: PhpProject/PhpProject/Functions 
define('PROJECT_PATH', dirname(FUNCTIONS_PATH));    // path to: PhpProject/PhpProject
define("COMMON_PATH", PROJECT_PATH . '/Common');    // path to: PhpProject/PhpProject/Common

$activePage = substr($_SERVER['REQUEST_URI'], 33, -4); // gets active URI and extracts page name

foreach (glob("../Classes/*.php") as $filename)
{
    require_once($filename);
}

require_once(FUNCTIONS_PATH . "/DatabaseAccess.php");

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


?>