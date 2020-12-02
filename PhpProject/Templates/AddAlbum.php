<?php 

require_once('../Functions/GeneralFunctions.php');

$pageTitle = "Add Album";
$_SESSION["lastPage"] = "AddAlbum";

// redirect if necessary
//requireLogin(); // you can comment this out to test your page without making login

// general page variables
//$currentUser = getUserFromID($_SESSION['userLogged']); // for testing, use next line
$currentUser = getUserFromID('user1'); // comment out/delete when not testing.
$name = $currentUser->getName();
$userId = $currentUser->getUserId();


// values for inputs and warning messages
// start empty and get assigned to previous values on form submit if form is invalid.
$albumTitle = "";
$selectedAccessibility = "";
$description = "";
$successMessage = "";
$albumTitleError = "";
$albumDescriptionError = "";

// validate on any submit since JS will be submitting the form.
if(isPostRequest()){  
    $albumTitle = $_POST['title'];
    $selectedAccessibility = $_POST['accessibility'];
    $description = $_POST['description'];
    
    // check for valid title and returns a code indicating error(s) or success.
    $resultCode = validateCreateNewAlbum($albumTitle, $description);
    
    if($resultCode == 0){
        // save album to DB
        $success = saveAlbum($albumTitle, $description, $userId, Date('Y-m-d'), $selectedAccessibility);
        if($success){
            // show success, reset inputs
            $link = TEMPLATES_URL . "/UploadPictures.php";
            $successHTML = "<span class='success'>Successfully created new album '$albumTitle'! "
                    . "Click <a href='$link'>here</a> to start adding photos. </span>";
            $albumTitle = "";
            $selectedAccessibility = "";
            $description = "";
        } else {
            // unknown error occurred.
            $successHTML = "<span class='error'>An error has occured.</span>";
        }
    }
    
    if ($resultCode == 1 || $resultCode == 4){
       $albumTitleError = " Album title required.";
    }
    
    if ($resultCode == 2 || $resultCode == 5){
       $albumTitleError = " Album title must be fewer than 256 characters.";
    }
    
    if ($resultCode == 3 || $resultCode == 4 || $resultCode == 5){
       $albumDescriptionError = " Album description must be fewer than 3000 characters.";
    }
}

include(COMMON_PATH . '\Header.php'); ?>

<body>
<div class="container">
    <h1>Create New Album</h1>
    <p>Welcome <?php print($name)?>! (Not you? Change user <a href="NewUser.php">here</a>)</p>
    <hr>
    <form name='newAlbum' method='POST' action="">
    <table class='createAlbumInputs'>
        <tr>
            <td><label for='title'>Title:</label></td>
            <td><input class='setWidth10 form-control' id='albumTitle' type='text' name='title' value='<?php print($albumTitle)?>'><span class='error'><?php print($albumTitleError)?></span></td>
        </tr>
        <tr>
            <td><label for='accessibility'>Accessible by:</label></td>
            <td>
                <select class='setWidth10 form-control' type='text' name='accessibility'>
                    <?php print(getAccessibilityDropdown($selectedAccessibility)) ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for='description'>Description:</label></td>
            <td><textarea class='setWidth10 form-control' id='albumDescription' rows='3' cols='30' name='description'><?php print($description)?></textarea><span class='error'><?php print($albumDescriptionError)?></span></td>
        </tr>
    </table>
        <button class='btn btn-primary' id='submitCreateAlbumButton' type='button' name='submitCreateAlbumButton'>Submit</button>
        <button class='btn btn-primary' type='reset' name='reset' value='reset'>Clear</button>  
        <?php print($successHTML) ?>
    </form>
</div>
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>