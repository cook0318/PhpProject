<?php 

require_once('../Functions/GeneralFunctions.php');
$pageTitle = "Sign Up";

unset($_SESSION['albumSelected']);
unset($_SESSION['pictureSelectedId']);
unset($_SESSION['userLogged']);

$id = $_POST['txtId'] ?? '';
$name = $_POST['txtName'] ?? '';
$phoneNumber = $_POST['telPhoneNumber'] ?? '';
$password = $_POST['txtPassword'] ?? '';
$confirmPassword = $_POST['txtConfirmPassword'] ?? '';

if(isPostRequest()) {
    $errors = [];
    
    //validates inputs
    if(validateId($id)) { $errors["id"] = validateId($id); }
    if(validateName($name)) { $errors["name"] = validateName($name); }
    if(validatePhone($phoneNumber)) { $errors["phone"] = validatePhone($phoneNumber); }
    if(validatePassword($password)) { $errors["password"] = validatePassword($password); }
    if(validateConfirmPassword($password, $confirmPassword)) { $errors["confirmPassword"] = validateConfirmPassword($password, $confirmPassword); }
    
    if(empty($errors)) {
        createUser($id, $name, $phoneNumber, $password);
        
        // Redirect to the page the user was attempting access, and they don't have a previously requested page,
        // redirect to MyAlbums as a default.
        if(isset($_SESSION["lastPage"])) {
            header('Location: ' . TEMPLATES_URL . '/' . $_SESSION["lastPage"] . '.php');
        } else {
            header('Location: ' . TEMPLATES_URL . '/MyAlbums.php');
        }
    }
}
?>

<?php include(COMMON_PATH . '\Header.php'); ?>

<body>
    <div class="container">
        <h1 class='m-0-p-10 m-b-10'>Sign Up</h1>
        <form class="form-horizontal" action="" method="post">
            <hr class="solid-divider" />
            <p class="text-muted">All fields are required</p>
            <div class="form-group row">
                <label for="txtId" class="control-label col-sm-2" style="text-align: left !important">User ID:</label>
                <div class="col-sm-4">
                    <input type="text" name="txtId" class="form-control" id="txtId" value="<?php echo $id; ?>"/>                    
                </div>
                <p class="error"><?php echo $errors["id"]; ?></p>
            </div>
            <div class="form-group row">
                <label for="txtName" class="control-label col-sm-2" style="text-align: left !important">Name:</label>
                <div class="col-sm-4">
                    <input type="text" name="txtName" class="form-control" id="txtName" value="<?php echo $name; ?>"/>
                </div>
                <p class="error"><?php echo $errors["name"]; ?></p>
            </div>
            <div class="form-group row">
                <div class="control-label col-sm-2 phone" style="text-align: left !important">
                    <label for="telPhoneNumber" class="no-pading no-margin">Phone Number:</label>
                    <p class="form-text text-muted no-pading no-margin"><small>(nnn-nnn-nnnn)</small></p>
                </div>
                <div class="col-sm-4">
                    <input type="tel" name="telPhoneNumber" class="form-control" id="telPhoneNumber" value="<?php echo $phoneNumber; ?>" />
                </div>
                <p class="error"><?php echo $errors["phone"]; ?></p>
            </div>
            <div class="form-group row">
                <label for="txtPassword" class="control-label col-sm-2" style="text-align: left !important">Password:</label>
                <div class="col-sm-4">
                    <input type="password" name="txtPassword" class="form-control" id="txtPassword" value="<?php echo $password ?>"/>
                </div>
                <p class="error"><?php echo $errors["password"]; ?></p>
            </div>
            <div class="form-group row">
                <label for="txtConfirmPassword" class="control-label col-sm-2" style="text-align: left !important">Password Again:</label>
                <div class="col-sm-4">
                    <input type="password" name="txtConfirmPassword" class="form-control" id="txtConfirmPassword" value="<?php echo $confirmPassword; ?>"/>
                </div>
                <p class="error"><?php echo $errors["confirmPassword"]; ?></p>
            </div>
            <div class="form-group row">
                <div class='col-sm-2'></div>
                <div class='col-sm-4'>
                    <button type="submit" class="btn-sm btn-primary">Submit</button>
                    <a href="<?php echo TEMPLATES_URL . '/NewUser.php' ?>"><button type="button" class="btn-sm btn-primary">Clear</button></a>
                </div>
            </div>
        </form>
    </div>    
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>