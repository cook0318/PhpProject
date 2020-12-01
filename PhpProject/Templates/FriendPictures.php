<?php 

require_once('../Functions/GeneralFunctions.php');

$pageTitle = "Friend Pictures";

$_SESSION["lastPage"] = "FriendPictures";

requireLogin();

$user = getUserFromID($_SESSION['userLogged']);

// for testing
$friend = getUserFromID('U0002');
$friendName = $friend->getName();
$friendId = $friend->getUserId();
$friendAlbums = getAllUserAlbums($friendId);

$albumSelected = $_POST['albumId'] ?? $friendAlbums[0]->getAlbumId();

//getAllUserAlbums($userId);
//getAlbumPictures($albumId);
//getComments($pictureId);
//createComment($commenterId, $pictureId, $commentText, $date);

?>

<?php include(COMMON_PATH . '\Header.php'); ?>

<body>
<div class="container">
    <h1><?php echo $friendName; ?>'s Pictures</h1>
    <div>
        <form action="" method="post">
            <select name="friendAlbum" id="friendAlbum">
                <?php foreach($friendAlbums as $a) { ?>
                    <option value="
                    <?php echo $a->getAlbumId(); ?>"
                    <?php if($a->getAlbumId() == $albumSelected) { echo "selected"; } ?>
                    ><?php echo $a->getTitle() . " - updated on " . $a->getDateUpdated(); ?>
                    </option>
                <?php } ?>
            </select>
            <input type="text" name="albumId" id="albumId" class="hidden" value="">
        </form>
    </div>
</div>
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>