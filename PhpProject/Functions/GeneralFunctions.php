<?php

// Non-Validating and Non-Database related functions.

session_start();

define('FUNCTIONS_PATH', dirname(__FILE__));            // path to: PhpProject/PhpProject/Functions 
define('PROJECT_PATH', dirname(FUNCTIONS_PATH));        // path to: PhpProject/PhpProject
define("COMMON_PATH", PROJECT_PATH . '/Common');        // path to: PhpProject/PhpProject/Common
define('ORIGINAL_PICTURES_DIR', PROJECT_PATH . "/UserPhotos/Original");
define('ALBUM_PICTURES_DIR', PROJECT_PATH . "/UserPhotos/AlbumPictures");
define('ALBUM_THUMBNAILS_DIR', PROJECT_PATH . "/UserPhotos/Thumbnails");

define('IMAGE_MAX_WIDTH', 1024);
define('IMAGE_MAX_HEIGHT', 800);

define('THUMB_MAX_WIDTH', 100);
define('THUMB_MAX_HEIGHT', 100);

$supportedImageTypes = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);

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
        
        $albumTitle = $album->getTitle();
        $albumDescription = $album->getDescription() == null ? "<em>No description</em>" : $album->getDescription();
        $photoCount = count($pictures) == 1 ? count($pictures) . " photo" : count($pictures) . " photos";
        $uploadDate = $album->getDateUpdated();
        $accessibilityDropdown = getAccessibilityDropdown($album->getAccessibilityCode());
        
        $card = <<<HEREDOC
        <div class="col-lg-4 col-sm-6 col-xs-12  d-flex align-items-stretch">
          <div class='card bg-light mb-3 mt-3' id='$albumId'>
            
            <div class="card-body">
                <h5 class="card-title"><button class='linkButton' type='submit' name='view' value='$albumId'</button>$albumTitle</h5>
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
                    <button class="btn btn-danger btn-sm deleteAlbumBtn" OnClick="confirmDelete('$albumTitle', $albumId)" type="button" value="$albumId">Delete Album</button>
                </div>
            </div>
          </div>
        </div>
HEREDOC;
        $returnHTML .= $card;
    }
    
    $returnHTML .= "<input type='hidden' id='deleteAlbumIdInput' name='delete' value=''</div>";
    return $returnHTML;
}

function ValidateFileUpload($files, $name){
    $allowed =  array('gif','png' ,'jpg', 'jpeg');
    $total = count($_FILES[$name]['name']);

    if (in_array(1, $files[$name]['error'], false))
    {
        return "Upload file is too large"; 
    }
    if (in_array(4, $files[$name]['error'], false))
    {
        return "No upload file specified"; 
    }

    //validates extensions and sizes for all files
    for ($i=0; $i < $total ; $i++) {
        $filename = $files[$name]['name'][$i];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if(!in_array($ext, $allowed)){
            return 'Accepted picture types: JPG(JPEG), GIF and PNG!';
        }
    }
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

function save_uploaded_file($destinationPath, $file, $fileIndex, $fileId)
{
    if (!file_exists($destinationPath))
    {
        mkdir($destinationPath);
    }

    $tempFilePath = $file['tmp_name'][$fileIndex];
    $filePath = $destinationPath."/".$file['name'][$fileIndex];

    $pathInfo = pathinfo($filePath);
    $dir = $pathInfo['dirname'];
    $ext = $pathInfo['extension'];
    $fileName = $fileId.'.'.$ext;

    $filePath = $destinationPath."/".$fileName;


    //make sure not to overwrite existing files 
    $i="";
    while (file_exists($filePath))
    {	
        $i++;
        $filePath = $dir."/".$fileName."_".$i.".".$ext;
    }
    move_uploaded_file($tempFilePath, $filePath);

    return $filePath;
}


function resamplePicture($filePath, $destinationPath, $maxWidth, $maxHeight)
{
    if (!file_exists($destinationPath))
    {
        mkdir($destinationPath);
    }


    $imageDetails = getimagesize($filePath);

    $originalResource = null;
    if ($imageDetails[2] == IMAGETYPE_JPEG) 
    {
        $originalResource = imagecreatefromjpeg($filePath);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_PNG) 
    {
        $originalResource = imagecreatefrompng($filePath);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_GIF) 
    {
        $originalResource = imagecreatefromgif($filePath);
    }
    $widthRatio = $imageDetails[0] / $maxWidth;
    $heightRatio = $imageDetails[1] / $maxHeight;
    $ratio = max($widthRatio, $heightRatio);

    $newWidth = $imageDetails[0] / $ratio;
    $newHeight = $imageDetails[1] / $ratio;

    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    $success = imagecopyresampled($newImage, $originalResource, 0, 0, 0, 0, $newWidth, $newHeight, $imageDetails[0], $imageDetails[1]);

    if (!$success)
    {
        imagedestroy(newImage);
        imagedestroy(originalResource);
        return "";
    }
    $pathInfo = pathinfo($filePath);
    $newFilePath = $destinationPath."/".$pathInfo['filename'];
    if ($imageDetails[2] == IMAGETYPE_JPEG) 
    {
        $newFilePath .= ".jpg";
        $success = imagejpeg($newImage, $newFilePath, 100);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_PNG) 
    {
        $newFilePath .= ".png";
        $success = imagepng($newImage, $newFilePath, 0);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_GIF) 
    {
        $newFilePath .= ".gif";
        $success = imagegif($newImage, $newFilePath);
    }

    imagedestroy($newImage);
    imagedestroy($originalResource);

    if (!$success)
    {
        return "";
    }
    else
    {
        return newFilePath;
    }
}


function rotateImage($filePath, $degrees)
{
    $imageDetails = getimagesize($filePath);

    $originalResource = null;
    if ($imageDetails[2] == IMAGETYPE_JPEG) 
    {
        $originalResource = imagecreatefromjpeg($filePath);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_PNG) 
    {
        $originalResource = imagecreatefrompng($filePath);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_GIF) 
    {
        $originalResource = imagecreatefromgif($filePath);
    }

    $rotatedResource = imagerotate($originalResource, $degrees, 0);

    if ($imageDetails[2] == IMAGETYPE_JPEG) 
    {
        $success = imagejpeg($rotatedResource, $filePath, 100);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_PNG) 
    {
        $success = imagepng($rotatedResource, $filePath, 0);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_GIF) 
    {
        $success = imagegif($rotatedResource, $filePath);
    }

    imagedestroy($rotatedResource);
    imagedestroy($originalResource);
}

?>