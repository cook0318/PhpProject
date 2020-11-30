<?php

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




?>
