<?php 

require_once('../Functions/GeneralFunctions.php');
$pageTitle = "Home";

?>

<?php include(COMMON_PATH . '\Header.php'); ?>

<body>
<div class="container">
    <h1 class='m-0-p-10 m-b-10'>Welcome to Algonquin College Online Course Registration</h1>
    <p>If you have never used this before, you have to <span><a href="NewUser.php">sign up</a></span> first.</p>
    <p>If you have already signed up, you can <span><a href="Login.php">log in</a></span> now.</p>
</div>
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>