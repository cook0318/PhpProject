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
$albums = getAllUserAlbums($userId);
$updatedAlbums = "";

if(isset($_POST['updateAccessibilities'])){
    $updatedAlbums = "<ul>";
    foreach($_POST as $selectName => $selectedValue){
        if(strpos($selectName, 'accessibility') !== false){
            $albumId = substr($selectName, 13);
            foreach($albums as $album){
                if($album->getAlbumId() == $albumId){
                    $currentAccessibilityValue = $album->getAccessibilityCode();
                    if($currentAccessibilityValue != $selectedValue){
                        if(updateAlbumAccessibility($selectedValue, $albumId)){
                            $albumTitle = $album->getTitle();
                            $updatedAlbums .= "<li>Successfully updated $albumTitle from $currentAccessibilityValue to $selectedValue.</li>";
                        }                        
                    }
                }
            }
        }
    }
    if($updatedAlbums != "<ul>"){
        $updatedAlbums .= "</ul>";
    }
    else{
        $updatedAlbums = "<p class='error'>You haven't changed any album's accesibility value.</p>";
    }
}



include(COMMON_PATH . '\Header.php'); ?>


<body>
<div class="container">
    <h1>My Albums</h1>
    <p>Welcome <?php print($name)?>! (Not you? Change user <a href="NewUser.php">here</a>)</p>
    <?php print($updatedAlbums) ?>
    <form class='relative' name='updateAlbums' method='POST' action="">
        <?php print(getAlbumCards($userId)) ?>
    </form>
</div>
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>