<?php 

require_once('../Functions/GeneralFunctions.php');

$pageTitle = "Friend Pictures";

$_SESSION["lastPage"] = "FriendPictures";

requireLogin();

$user = getUserFromID($_SESSION['userLogged']);

?>

<?php include(COMMON_PATH . '\Header.php'); ?>

<body>
<div class="container">
    <h1>My Pictures</h1>
</div>
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>