<?php 

require_once('../Functions/GeneralFunctions.php');

$pageTitle = "My Pictures";

$_SESSION["lastPage"] = "MyPictures";

//requireLogin();

?>

<?php include(COMMON_PATH . '\Header.php'); ?>

<body>
<div class="container">
    <h1>My Pictures</h1>
</div>
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>