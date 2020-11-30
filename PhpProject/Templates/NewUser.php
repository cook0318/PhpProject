<?php 

require_once('../Functions/GeneralFunctions.php');
$pageTitle = "Sign Up";

$id = $_POST['txtId'] ?? '';
$name = $_POST['txtName'] ?? '';
$phoneNumber = $_POST['telPhoneNumber'] ?? '';
$password = $_POST['txtPassword'] ?? '';
$confirmPassword = $_POST['txtConfirmPassword'] ?? '';

if(isPostRequest()) {
    $errors = [];
    
    //validates inputs
    if(validateId($id)) { $errors["id"] = ValidateId($id); }
    if(validateName($name)) { $errors["name"] = ValidateName($name); }
    if(validatePhone($phoneNumber)) { $errors["phone"] = ValidatePhone($phoneNumber); }
    if(validatePassword($password)) { $errors["password"] = ValidatePassword($password); }
    if(validateConfirmPassword($password, $confirmPassword)) { $errors["confirmPassword"] = ValidateConfirmPassword($password, $confirmPassword); }
    
    if(empty($errors)) {
        createUser($id, $name, $phoneNumber, $password);
        
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
        <h1>Sign Up</h1>
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
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="<?php echo TEMPLATES_URL . '/NewUser.php' ?>"><button type="button" class="btn btn-primary">Clear</button></a>
        </form>
    </div>    
</body>

<?php include(COMMON_PATH . '\Footer.php'); ?>