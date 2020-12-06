<?php 

require_once('../Functions/GeneralFunctions.php');

$pageTitle = "Friend Pictures";

if($_SESSION["lastPage"] != "FriendPictures") {
    unset($_SESSION['albumSelected']);
    unset($_SESSION['pictureSelectedId']);
}

$_SESSION["lastPage"] = "FriendPictures";

requireLogin();

$user = getUserFromID($_SESSION['userLogged']);
$name = $user->getName();

if(isset($_GET['id']) == false){
    header('Location: ' . TEMPLATES_URL . "/MyFriends.php");
}

$friend = getUserFromID(idEscape($_GET['id']));
if($friend == null){
    header('Location: ' . TEMPLATES_URL . "/MyFriends.php");
}

if(getFriendshipStatus($user->getUserId(), $friend->getUserId())->getStatus() != "accepted") {
    header('Location: ' . TEMPLATES_URL . "/MyFriends.php");
}

$friendName = $friend->getName();
$friendId = $friend->getUserId();
$friendAlbums = getAllUserAlbums($friendId);
$hasAlbums = getFirstSharedAlbumId($friendId) != 0 ? true : false;

if($hasAlbums) {

    // Saves album selected in SESSION / creates default value if fresh session
    if(!empty($_POST['albumId'])){
        unset($_SESSION['pictureSelectedId']);
        $_SESSION['albumSelected'] = $_POST['albumId'];
    }
    if(!isset($_SESSION['albumSelected'])){
        $_SESSION['albumSelected'] = $_POST['albumId'] ?? getFirstSharedAlbumId($friendId);
    }
    // Gets all pictures from album selected
    $albumPictures = getAlbumPictures($_SESSION['albumSelected']);
    $hasPictures = count($albumPictures) > 0 ? true : false;    
    
    if($hasPictures){
        // Saves picture selected in SESSION / creates default value if fresh session
        if(!empty($_POST['pictureId'])){
            $_SESSION['pictureSelectedId'] = $_POST['pictureId'];
        }
        if(!isset($_SESSION['pictureSelectedId'])){
            $_SESSION['pictureSelectedId'] = $_POST['pictureId'] ?? $albumPictures[0]->getPictureId();
        }
        // Gets picture selected and its comments
        $pictureSelected = getPictureById($_SESSION['pictureSelectedId']); // Class picture selected
        $pictureComments = getComments($_SESSION['pictureSelectedId']); // Comments picture selected
    }
}

$comment = $_POST["newComment"] ?? '';

// Saves new comments
if(isPostRequest() && $_POST["newCommentAdded"] == 1) {
    $error = [];
    
    //validates input
    if(validateComment($_POST["newComment"])) { $error["comment"] = validateComment($_POST["newComment"]); }

    if(empty($error)){
        createComment($user->getUserId(), $_SESSION['pictureSelectedId'], $_POST["newComment"], date("Y-m-d H:i:s"));
        header("Refresh:0");
    }
}

?>

<?php include(COMMON_PATH . '\Header.php'); ?>

<body>
<div class="container">
    <h1 class="m-0-p-10 m-b-10"><?php echo $friendName; ?>'s Pictures</h1>
    <p>Welcome <b><?php print $name;?></b>! (Not you? Change user <a href="Login.php">here</a>)</p>
    <hr>
    <?php if($hasAlbums) { ?> <!-- Page will be displayed case there are albums to be shown -->
    <div class="row">
        <div class="col-9">
            <div id="albumChoice m-0-p-10">
                <form action="" method="post"> <!-- Dropdown List of Albums to select -->
                    <select name="friendAlbum" id="friendAlbum" class="form-control">
                        <?php foreach($friendAlbums as $a) { 
                            if($a->getAccessibilityCode() == "shared") { ?>
                            <option value="
                            <?php echo $a->getAlbumId(); ?>"
                            <?php if($a->getAlbumId() == $_SESSION['albumSelected']) { echo "selected"; } ?>
                            ><?php echo $a->getTitle() . " - updated on " . $a->getDateUpdated(); ?>
                            </option>
                        <?php }} ?>
                    </select>
                    <input type="text" name="albumId" id="albumId" class="hidden" value="">
                </form>
            </div>

            <?php if($hasPictures) { ?> <!-- Page will be displayed case there are pictures to be shown -->
            <div class="m-0-p-10"> <!-- Title of picture selected -->
                <h2 class="text-center"><?php echo $pictureSelected->getTitle(); ?></h2>
            </div>

            <div class='p-b-10' id="gallery">
                <div id="currentPicture"> <!-- Selected picture - big image display -->
                    <img 
                        src="../UserPhotos/AlbumPictures/<?php echo $pictureSelected->getFileName(); ?>" 
                    alt="<?php echo $pictureSelected->getTitle(); ?>" 
                    class="img-current">
                </div>

                <div id="thumbnail-bar"> <!-- Bar with thumbnails of pictures to select -->
                    <form action="" method="post">
                        <?php foreach($albumPictures as $p) { ?>
                            <div class="thumbnail-item" id="<?php echo $p->getPictureId(); ?>" album-id="<?php echo $p->getAlbumId(); ?>">
                                <img 
                                    src="../UserPhotos/Thumbnails/<?php echo $p->getFileName(); ?>" 
                                alt="<?php echo $p->getTitle(); ?>" 
                                class="img-thumbnail <?php if($p->getPictureId() == $_SESSION['pictureSelectedId']) { echo "bg-info"; } ?>">
                            </div>
                        <?php } ?>
                        <input type="text" name="pictureId" id="pictureId" class="hidden" value="">
                    </form>
                </div>
            </div>
            <?php }?>
        </div>
        
        <?php if($hasPictures) { ?> <!-- Page will be displayed case there are pictures to be shown -->
        <div class="col-3">
            <div class="col m-0-p-50"></div>
            <div id="description-comments"> <!-- Box with selected picture Description and Comments -->
                <h4>Description:</h4>
                <p><?php echo $pictureSelected->getDescription(); ?></p>
                <h4>Comments:</h4>
                <?php foreach($pictureComments as $c) { ?>
                    <p>
                        <span class="font-italic text-primary">
                            <?php echo getUserFromID($c->getAuthorId())->getName() . " (" . $c->getDate() . "): " ?>
                        </span>
                        <?php echo $c->getCommentText(); ?>
                    </p>
                <?php } ?>
            </div>  

            <form action="" method="post"> <!-- Comment box - to submit new comments -->
                <div class="m-0-p-10">
                    <textarea name="newComment" id="newComment" class="form-control" placeholder="Leave comment..."><?php echo $comment; ?></textarea>
                </div>
                <input type="text" name="newCommentAdded" id="newCommentAdded" class="hidden" value="0">
                <p class="error"><?php echo $error["comment"]; ?></p>
                <input type="submit" id="addComment" class="btn btn-primary" value="Add Comment">
            </form>
        </div>
        <?php } else { ?>
        
        <div class="col-9">
            <p class="text-center m-0-p-10 m-b-10">There are no pictures to be shown.</p>           
        </div>

    </div>

    <?php }} else { ?> <!-- Page will be displayed case there are NO albums to be shown -->
        <p class="text-center m-0-p-10 m-b-10">There are no albums to be shown.</p>
    <?php }?>

</div>
</div>
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>