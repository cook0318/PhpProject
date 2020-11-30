<?php

require_once('../Functions/GeneralFunctions.php');
require_once(FUNCTIONS_PATH . "/DatabaseAccess.php");

// Functions that validate user inputs.

// Checks if Album Title is valid (not empty).
// Returns true if valid, else false.
function validateCreateNewAlbum($albumTitle){
    $valid = true;
    if(is_null($albumTitle) || trim($albumTitle) == ""){
        $valid = false;
    }
    
    return $valid;
}

// Checks if new ID is not empty and not duplicate.
// Returns 0 if valid, or the respective error if an error exists.
function validateId ($id) {
    $PDO = Connect();

    if(notEmpty($id)) {            
        $sql = 'SELECT COUNT(*) as num FROM User WHERE user_id = :userId';
        $preparedStatement = $PDO->prepare($sql);
        $preparedStatement->execute(['userId' => idEscape($id)]);

        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        
        if($row['num'] > 0) {
            return "A user with this ID has already signed up";
        }
    } else {
        return "User ID cannot be blank.";
    }
}

// Checks if new Name is not empty.
// Returns 0 if valid, or the respective error if an error exists.
function validateName ($name) {
    if(!notEmpty($name)) {
        return "Name cannot be blank.";
    }
}

// Checks if new Phone Number is not empty and matches the correct format.
// Returns 0 if valid, or the respective error if an error exists.
function validatePhone ($phone) {
    if(notEmpty($phone)){
        $regex = "/^[1-9][0-9]{2}-[1-9][0-9]{2}-[0-9]{4}$/i";      
        if (!preg_match($regex, $phone)) {
            return "Phone Number must be in the format nnn-nnn-nnnn.";
        } 
    } else {
        return "Phone Number cannot be blank.";
    }
}

// Checks if new Password is not empty and matches the correct format.
// Returns 0 if valid, or the respective error if an error exists.
function validatePassword ($password) {
    if(notEmpty($password)){
        $regex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{6,}$/";
        if (!preg_match($regex, $password)) {
            return "Password must have at least 6 characters, contain at least one upper case, one lower case and one number.";
        } 
    } else {
        return "Password cannot be blank.";
    }
}

// Checks if Confirm Password is not empty and matches the New Password.
// Returns 0 if valid, or the respective error if an error exists.
function validateConfirmPassword ($password, $confirmPassword) {
    if(notEmpty($confirmPassword)){            
        if ($password != $confirmPassword) {
            return "Password and password confirmation do not match";
        } 
    } else {
        return "Password confirmation cannot be blank.";
    }
}

?>
