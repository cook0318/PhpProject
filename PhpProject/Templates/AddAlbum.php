<?php 

foreach (glob("../Functions/*.php") as $filename)
{
    require_once($filename);
}
foreach (glob("../Classes/*.php") as $filename)
{
    require_once($filename);
}

session_start();
date_default_timezone_set("America/Toronto");

// TESTING PURPOSES:////////////
$_SESSION['currentUser'] = getUserFromID('user1');
////////////////////////////////



// Retrieve session data for currently logged in user.
if(isset($_SESSION['currentUser']) == false){
    header('Location: Login.php');
}

// general page variables
$name = $_SESSION['currentUser']->getName();
$userId = $_SESSION['currentUser']->getUserId();
$pageTitle = "Add Album";

// values for inputs and warning messages
// start empty and get assigned to previous values on form submit if form is invalid.
$albumTitle = "";
$selectedAccessibility = "";
$description = "";
$successMessage = "";
$albumTitleError = "";

// validate on any submit since JS will be submitting the form.
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $albumTitle = $_POST['title'];
    $selectedAccessibility = $_POST['accessibility'];
    $description = $_POST['description'];
    
    // check for valid title
    $validInput = validateCreateNewAlbum($albumTitle);
    
    if($validInput == true){
        // save album to DB
        $success = saveAlbum($albumTitle, $description, $userId, Date('Y-m-d'), $selectedAccessibility);
        if($success){
            // show success, reset inputs
            $successHTML = "<span class='success'>Successfully created new album '$albumTitle'!</span>";
            $albumTitle = "";
            $selectedAccessibility = "";
            $description = "";
        } else {
            // unknown error occurred.
            $successHTML = "<span class='error'>An error has occured.</span>";
        }
    }
    else{
        $albumTitleError = "  Album title is required.";
    }
}
?>

<?php include(COMMON_PATH . '\Header.php'); ?>

<body>
<div class="container">
    <h1>Create New Album</h1>
    <p>Welcome <?php print($name)?>! (Not you? Change user <a href="NewUser.php">here</a>)</p>
    <form name='newAlbum' method='POST' action="">
    <table>
        <tr>
            <td><label for='title'>Title:</label></td>
            <td><input id='albumTitle' type='text' name='title' value='<?php print($albumTitle)?>'><span class='error'><?php print($albumTitleError)?></span></td>
        </tr>
        <tr>
            <td><label for='accessibility'>Accessibility:</label></td>
            <td>
                <select type='text' name='accessibility'>
                    <?php print(getAccessibilityDropdown($selectedAccessibility)) ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for='description'>Description:</label></td>
            <td><textarea id='albumDescription' rows='3' cols='30' name='description'><?php print($description)?></textarea></td>
        </tr>
    </table>
        <button class='btn btn-primary' id='submitCreateAlbumButton' type='button' name='submitCreateAlbumButton'>Submit</button>
        <button class='btn btn-primary' type='reset' name='reset' value='reset'>Clear</button>  
        <?php print($successHTML) ?>
    </form>
</div>
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>


<!-- when the button clicks, first JS parses the inputs. it decides whether to submit the form or not.  -->