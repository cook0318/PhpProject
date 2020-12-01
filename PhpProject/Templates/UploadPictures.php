<?php 

require_once('../Functions/GeneralFunctions.php');

$pageTitle = "Upload Pictures";

$_SESSION["lastPage"] = "UploadPictures";

requireLogin();

?>

<?php include(COMMON_PATH . '\Header.php'); ?>

<body>
<div class="container">
    <h1>Upload Pictures</h1>
</div>
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>