<?php 

require_once('../Functions/GeneralFunctions.php');

$pageTitle = "My Albums";
$_SESSION["lastPage"] = "MyAlbums";

// redirect if necessary
requireLogin();

$currentUser = getUserFromID($_SESSION['userLogged']);

// general page variables
$name = $currentUser->getName();
$userId = $currentUser->getUserId();
$albums = getAllUserAlbums($userId);
$updatedAlbums = "";

// will be true if user has clicked 'Update Accessibilties' button.
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
                            $updatedAlbums .= "<li class='success'>Successfully updated $albumTitle from $currentAccessibilityValue to $selectedValue.</li>";
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
else if(isPostRequest()){
    // will be true if user clicks album title, which are actually submit buttons,
    // or if user clicks to delete an album.
    foreach($_POST as $name => $albumId){
        if($name == "view"){
            $_SESSION['albumSelected'] = $albumId;
            header('Location: ' . TEMPLATES_URL . "/MyPictures.php");
        }
        if($name == "delete"){
            $albumToDelete = getAlbumFromId($albumId);
            $albumTitle = $albumToDelete->getTitle();
            $success = deleteAlbum($albumId);
            $updatedAlbums = $success ? "<p class='success'>Successfully deleted '" . $albumTitle . "'. </p>" :
                "<p class='error'> An error occured and '" . $albumTitle . "' was not deleted.</p>" ;
        }
    }
}


include(COMMON_PATH . '\Header.php'); ?>


<body>
<div class="container">
    <h1 class='m-0-p-10 m-b-10'>My Albums</h1>
    <p>Welcome <b><?php print $name;?></b>! (Not you? Change user <a href="Login.php">here</a>).</p>
    <hr> 
    <?php print($updatedAlbums) ?>
    <form id='myAlbumsForm' class='relative' name='updateAlbums' method='POST' action="">
        <?php print(getAlbumCards($userId)) ?>
    </form>
</div>
</div>
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>