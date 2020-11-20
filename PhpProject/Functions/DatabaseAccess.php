<?php

// Functions requiring database access.

function Connect(){
    global $dbConnection;
    if(is_null($dbConnection)){
        $connectionInfo = parse_ini_file('DatabaseInfo/db.ini');
        extract($connectionInfo);
        $dbConnection = new PDO($dsn, $user, $password);
    }
    return $dbConnection;
}

?>