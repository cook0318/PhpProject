<?php 

require_once('../Functions/GeneralFunctions.php');

$pageTitle = "My Friends";

$_SESSION["lastPage"] = "MyFriends";

requireLogin();

?>

<?php include(COMMON_PATH . '\Header.php'); ?>

<body>
<div class="container">
    <h1>My Friends</h1>
</div>
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>