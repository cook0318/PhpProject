<?php

// Non-Validating and Non-Database related functions.

session_start();

define('FUNCTIONS_PATH', dirname(__FILE__));            // path to: PhpProject/PhpProject/Functions 
define('PROJECT_PATH', dirname(FUNCTIONS_PATH));        // path to: PhpProject/PhpProject
define("COMMON_PATH", PROJECT_PATH . '/Common');        // path to: PhpProject/PhpProject/Common

$templates_end = strpos($_SERVER['SCRIPT_NAME'], '/Templates') + 10;
define("TEMPLATES_URL", substr($_SERVER['SCRIPT_NAME'], 0, $templates_end)); // URL path to /PhpProject/Templates

$activePage = substr($_SERVER['REQUEST_URI'], $templates_end + 1, -4); // extracts page name

foreach (glob("../Classes/*.php") as $filename)
{
    require_once($filename);
}
require_once(FUNCTIONS_PATH . "/DatabaseAccess.php");
require_once(FUNCTIONS_PATH . "/ValidationFunctions.php");

date_default_timezone_set("America/Toronto");


function getDefaultPhoto(){
    return new Picture(0, 0, "NoPhotos.jpg", "No Photo Title", "Filler Photo", strtotime("1 January 1900"));
}

// Gets the HTML for the selectable Accessibility dropdown on the Create an Album page.
// Input parameter is the currently selected option, if the form ends up being invalid.
function getAccessibilityDropdown($selectedAccessibility){
    $accessibilityCodes = getAllAccessibilityCodes();
    $returnHTML = "";
    foreach($accessibilityCodes as $code){
        $selected = "";
        $name = $code->getAccessibilityCode();
        $optionText = $name == "private" ? "Only you" : "You and friends";
        if($name == $selectedAccessibility){
            $selected = "selected";
        }
        $option = "<option value='$name' $selected>$optionText</option>";
        $returnHTML .= $option;
    }
    
    return $returnHTML;
}

function getAlbumCards($userId){
    
    $albums = getAllUserAlbums($userId);
    if(count($albums) == 0){
        return "<br><br><p>You do not currently have any albums.</p>";    
    }
    
    $returnHTML = "<div class='controlHolder'><a href='AddAlbum.php'>Create an album</a><button class='updateAccessibilitiesBtn btn btn-primary' "
            . "type='submit' name='updateAccessibilities'>Update Accessibilities</button></div><div class='card-deck'>";
    foreach($albums as $album){
        $albumId = $album->getAlbumId();
        $pictures = getAlbumPictures($album->getAlbumId());
        $coverPhoto = $pictures[0];
        if($coverPhoto == null){
            $coverPhoto = getDefaultPhoto();
        }
        $coverPhotoPath = "../UserPhotos/{$coverPhoto->getFileName()}";
        if(file_exists($coverPhotoPath)==false){
            $coverPhotoPath = "../UserPhotos/PhotoUnavailable.jpg";
        }
        $coverPhotoTitle = $coverPhoto->getTitle();
        $albumTitle = $album->getTitle();
        $albumDescription = $album->getDescription() == null ? "<em>No description</em>" : $album->getDescription();
        $photoCount = count($pictures) == 1 ? count($pictures) . " photo" : count($pictures) . " photos";
        $uploadDate = $album->getDateUpdated();
        $accessibilityDropdown = getAccessibilityDropdown($album->getAccessibilityCode());
        
        
        //<img class="card-img-top" src="$coverPhotoPath" alt="$coverPhotoTitle">
        $card = <<<HEREDOC
        <div class="col-lg-4 col-sm-6 col-xs-12  d-flex align-items-stretch">
          <div class='card bg-light mb-3 mt-3' id='$albumId'>
            
            <div class="card-body">
                <h5 class="card-title"><button class='linkButton' type='submit' name='viewButton' value='$albumId'</button>$albumTitle</h5>
                <p class="card-text">$albumDescription</p>
            </div>
            <div class="card-footer">
                <p class="card-text">$photoCount</p>
                <p class="card-text"><b>Uploaded:</b> $uploadDate</p>
                <p class="card-text"><b>Accessible by:</b> 
                    <select type='text' class='form-control smallSelect' name='accessibility$albumId'>
                        $accessibilityDropdown
                    </select>
                </p>
                <div class="buttonCardContainer">
                    <button class="btn btn-danger btn-sm deleteAlbumBtn" OnClick="confirmDelete('$albumTitle')" type="button" name="deleteAlbum$albumId">Delete Album</button>
                </div>
            </div>
          </div>
        </div>
HEREDOC;
        $returnHTML .= $card;
    }
    
    $returnHTML .= "</div>";
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