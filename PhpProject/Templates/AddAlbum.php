<?php 

// S T A R T     S U G G E S T I O N -----------------------------------------------------------------------------------------------
    // B I L L Y
    // I included all "require_once" in the GeneralFunctions.php, so we don't need to call each one of the required files
    // separately: we can call only require_once(generalFunctions) and that's all.
    // Also, I included the session_start() and date_default_timezone_set in the GeneralFuntions file, so we dont need to include
    // those infos either.

    // For the Session"currentUser", I created the Session["userLogged"] that will be activated after a successful LogIn or SignUp,
    // so I would suggest that we take the ID from there and only call the getUserFromID in each page we will need it.

    // Also, I created the function requireLogin that will redirect the user to the Login page case no user is logged in.

    // Please check below the suggestions I made and please, please, please ignore what you think is not useful!

    // Also, I created a "cleaner" for the id that takes off all whitespaces and puts everything in uppercase. So, for testing
    // purposes, it is a good idea that you create a user through the NewUser.php page (it will also hash the password). Then, you
    // can just comment out the require_login() function and include the $currentUser as shown below

    // one last thing, I created a function isPostRequest, so if you want you can use line 82 instead of 83

// ----------------------------- I N C L U D E -------------------------------

// require_once('../Functions/GeneralFunctions.php');

// $pageTitle = "Add Album";

// $_SESSION["lastPage"] = "AddAlbum";

// requireLogin(); // you can comment this out to test your page without making login

// $currentUser = getUserFromID($_SESSION['userLogged']); // for testing, use next line
// // $currentUser = getUserFromID('U0001'); // for testing, use next line

// $name = $currentUser->getName();
// $userId = $currentUser->getUserId();

// ----------------------------- E X C L U D E -------------------------------

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

// E N D     S U G G E S T I O N -----------------------------------------------------------------------------------------------


// values for inputs and warning messages
// start empty and get assigned to previous values on form submit if form is invalid.
$albumTitle = "";
$selectedAccessibility = "";
$description = "";
$successMessage = "";
$albumTitleError = "";

// validate on any submit since JS will be submitting the form.

// if(isPostRequest()){   A N O T H E R    S U G G E S T I O N --------------------------------------------------------------------
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

include(COMMON_PATH . '\Header.php'); ?>

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
            <td><label for='accessibility'>Accessible by:</label></td>
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