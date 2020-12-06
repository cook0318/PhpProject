<?php

require_once('../Functions/GeneralFunctions.php');
$pageTitle = "Log In";

unset($_SESSION['albumSelected']);
unset($_SESSION['pictureSelectedId']);
unset($_SESSION['userLogged']);

$id = $_POST['txtId'] ?? '';

$password = $_POST['txtPassword'] ?? '';

if(isPostRequest()) {
    $errors = [];
    
    //validates inputs
    if(!notEmpty($id)) { $errors["id"] = "Id cannot be blank."; }
    if(!notEmpty($password)) { $errors["password"] = "Password cannot be blank."; }
    if(empty($errors)) {
        if(!logIn($id, $password)) { $errors["login"] = "Incorrect ID and/or Password."; }
    }
    
    if(empty($errors)) {
        if(isset($_SESSION["lastPage"])) {
            header('Location: ' . TEMPLATES_URL . '/' . $_SESSION["lastPage"] . '.php');
        } else {
            header('Location: ' . TEMPLATES_URL . '/Index.php');
        }
    }
}
?>

<?php include(COMMON_PATH . '\Header.php'); ?>

<body>
    <div class="container">
        <h1 class='m-0-p-10 m-b-10'>Log In</h1>
        <form class="form-horizontal" action="" method="post">
            <hr class="solid-divider" />
            <p class="text-muted">You need to <span><a href="NewUser.php">sign up</a></span> if you are a new user</p>
            <p class="error"><?php echo $errors["login"]; ?></p>
            <div class="form-group row">
                <label for="txtId" class="control-label col-sm-2" style="text-align: left !important">Student ID:</label>
                <div class="col-sm-4">
                    <input type="text" name="txtId" class="form-control" id="txtId" value="<?php echo $id; ?>"/>                    
                </div>
                <p class="error"><?php echo $errors["id"]; ?></p>
            </div>
            <div class="form-group row">
                <label for="txtPassword" class="control-label col-sm-2" style="text-align: left !important">Password:</label>
                <div class="col-sm-4">
                    <input type="password" name="txtPassword" class="form-control" id="txtPassword"/>
                </div>
                <p class="error"><?php echo $errors["password"]; ?></p>
            </div>
            <div class="form-group row">
                <div class='col-sm-2'></div>
                <div class='col-sm-4'>
                    <button type="submit" class="btn-sm btn-primary">Submit</button>
                    <a href="<?php echo TEMPLATES_URL . '/Login.php' ?>"><button type="button" class="btn-sm btn-primary">Clear</button></a>
                </div>
            </div>
        </form>
    </div>    
</body>



<?php include(COMMON_PATH . '\Footer.php'); ?>
