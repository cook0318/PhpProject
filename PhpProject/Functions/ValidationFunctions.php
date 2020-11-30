<?php

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




?>
