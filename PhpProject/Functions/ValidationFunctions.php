<?php

require_once('../Functions/GeneralFunctions.php');
require_once(FUNCTIONS_PATH . "/DatabaseAccess.php");

// Functions that validate user inputs.

// Checks if Album Title and Description are valid (not empty and within character limit).
// Returns a success or error code:
// 0 : valid
// 1 : no title
// 2 : title too long
// 3 : description too long
// 4 : no title, description too long
// 5 : both too long
function validateCreateNewAlbum($albumTitle, $albumDescription){
    $code = 0;
    
    if(is_null($albumTitle) || trim($albumTitle) == ""){
        $code = 1;
        if(strlen($albumDescription) >= 3000){
            $code = 4;
        }
        
        return $code;
    }
    
    if(strlen($albumTitle) >= 256){
        $code = 2;
        if(strlen($albumDescription) >= 3000){
            $code = 5;
        }
        
        return $code;
    }
    
    if(strlen($albumDescription) >= 3000){
        $code = 3;
    }
    
    return $code;
}

// Checks if new ID is not empty and not duplicate.
// Returns 0 if valid, or the respective error if an error exists.
function validateId ($id) {
    $PDO = Connect();

    if(notEmpty($id)) {
        if (strlen($id) <= 16) {
            $sql = 'SELECT COUNT(*) as num FROM User WHERE userId = :userId';
            $preparedStatement = $PDO->prepare($sql);
            $preparedStatement->execute(['userId' => idEscape($id)]);
            
            $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
            
            if($row['num'] > 0) {
                return "A user with this ID has already signed up";
            }            
        } else {
            return "User ID cannot exceed 16 characters.";
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
    if (strlen($name) > 256) {
        return "Name cannot exceed 256 characters.";
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
        $regex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{6,256}$/";
        if (!preg_match($regex, $password)) {
            return "Password must have between 6 and 256 characters, contain at least one upper case, one lower case and one number.";
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

// Checks if new Comment is not empty and has the correct length.
// Returns 0 if valid, or the respective error if an error exists.
function validateComment ($comment) {
    if(notEmpty($comment)){            
        if (strlen($comment) > 3000) {
            return "Your comment is too long.";
        } 
    } else {
        return "Comment cannot be blank.";
    }
}

// ensures title is less than 256 characters.
function ValidatePictureTitle($pictureTitle){
    if (strlen($pictureTitle) > 256) {
        return "Title cannot exceed 256 characters.";
    }
}

// ensures description is less than 3000 characters.
function ValidatePictureDescription($pictureDescription){
    if (strlen($pictureDescription) > 3000) {
        return "Description cannot exceed 3000 characters.";
    }
}

?>
