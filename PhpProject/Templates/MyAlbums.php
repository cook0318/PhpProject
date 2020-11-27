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
$pageTitle = "My Albums";





include(COMMON_PATH . '\Header.php'); ?>


<body>
<div class="container">
    <h1>My Albums</h1>
    <p>Welcome <?php print($name)?>! (Not you? Change user <a href="NewUser.php">here</a>)</p>
    <a href="AddAlbum.php">Create an album</a>
    <?php print(getAlbumCards($userId)) ?>
    
</div>
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>