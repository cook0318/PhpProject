<?php

// Functions requiring database access.

foreach (glob("Classes/*.php") as $filename)
{
    include $filename;
}


$dbConnection = null;

// The first time this function is called, it creates and returns a PDO object. It then
// sets the global variable $dbConnection to this object, so all subsequent connections
// do not need to create a new PDO each time.
function Connect(){
    global $dbConnection;
    if(is_null($dbConnection)){
        $connectionInfo = parse_ini_file('DatabaseInfo/db.ini');
        extract($connectionInfo);
        $dbConnection = new PDO($dsn, $user, $password);
    }
    return $dbConnection;
}

// Returns a list of all Accessibility objects.
function getAllAccessibilityCodes(){
    $accessibilityCodes = [];
    $PDO = Connect();
    $sql = "SELECT accessibility_code, description FROM Accessibility";
    $preparedStatement = $PDO->prepare($sql);
    $preparedStatement->execute();
    foreach($preparedStatement as $row){
        $accessibilityCode = new Accessibility($row['accessibility_code'], $row['description']);
        $accessibilityCodes[] = $accessibilityCode;
    }
    
    return $accessibilityCodes;
}

// Returns a list of Album objects owned by a given user ID.
function  getAllUserAlbums($userId){
    $albums = [];
    $PDO = Connect();
    $sql = "SELECT album_id, title, description, date_updated, accessibility_code "
                . "FROM Album  WHERE owner_id = :userId";
    $preparedStatement = $PDO->prepare($sql);
    $preparedStatement->execute(['userId' => $userId]);
    foreach($preparedStatement as $row){
        $album = new Album($row['album_id'], $row['title'], $row['description'], 
                $row['date_updated'], $row['owner_id'], $row['accessibility_code']);
        $albums[] = $album;
    }
    
    return $albums;
}

// Saves an Album. Returns true if the save was successful, and false if
// the save was unsuccessful.
function saveAlbum($title, $description, $ownerId, $dateUpdated, $accessibilityCode){    
    $PDO = Connect();
    $sql = "INSERT INTO Album (title, description, owner_Id, date_updated, accessibility_code) "
            . "VALUES( :title, :description, :userId, :dateUpdated, :accessibilityCode)";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['title' => $title, 'description' => $description,
        'userId' => $ownerId, 'dateUpdated' => $dateUpdated, 'accessibilityCode' => $accessibilityCode]);
    
    return $success;
}

// Updates an album's accessibility code. Returns true if the update was successful, and false if
// the update was unsuccessful.
function updateAlbumAccessibility($accessibilityCode, $albumId){
    $PDO = Connect();
    $sql = "UPDATE Album SET accessibility_code = :accessibilityCode WHERE album_id = :albumId";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['accessibilityCode' => $accessibilityCode, 'albumId' => $albumId]);
    
    return $success;
}

// Returns a user object given an ID and Password, or returns null if the ID and password do not match.
function getUserFromIdAndPassword($id, $password){
    $user = null;
    $PDO = Connect();
    $sql = "SELECT userId, name, phone FROM User WHERE userId = :userId AND password = :password";
    $preparedStatement = $PDO->prepare($sql);
    if($preparedStatement->execute(['userId' => $id, 'password' => $password])){
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        $user = new User($id, $row['Name'], $row['Phone'], $password);
    }
    
    return $user;
}


// Returns a user object given an ID, or returns null if the ID does not exist.
function getUserFromID($id){
    $user = null;
    $PDO = Connect();
    $sql = "SELECT userId, name, phone FROM User WHERE userId = :userId";
    $preparedStatement = $PDO->prepare($sql);
    if($preparedStatement->execute(['userId' => $id])){
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        $user = new User($id, $row['name'], $row['phone']);
    }
    
    return $user;
}

// Saves a user to the database. Returns true if save successful, otherwise false.
function createUser($id, $name, $phone, $password){
    $PDO = Connect();
    $sql = "INSERT INTO User VALUES( :userId, :name, :phone, :password)";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['userdId' => $id, 'name' => $name, 'phone' => $phone, 'password' => $password]);
    
    return $success;
}

// Saves a comment to the database. Returns true if save successful, otherwise false.
function createComment($commenterId, $pictureId, $commentText, $date){
    $PDO = Connect();
    $sql = "INSERT INTO Comment VALUES(null, :authorId, :pictureId, :commentText, :date)";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['authorId' => $commenterId, 'pictureId' =>
        $pictureId, 'commentText' => $commentText, 'date' => $date]);
    
    return $success;
}

// Gets all comments for a given photo ID. 
//Returns a list in format [Comment Author as a User Object, Comment Object]
function getComments($pictureId){
    $result = [];
    $PDO = Connect();
    $sql = "SELECT comment_id, comment_text, date, user_id, name, phone FROM comment "
            . "INNER JOIN User ON Comment.author_id = User.user_id WHERE picture_id = :pictureId";
    $preparedStatement = $PDO -> prepare($sql);
    $preparedStatement->execute(['pictureId' => $pictureId]);
    foreach($preparedStatement as $row){
        $userAndComment = [];
        $user = new User($row['user_id'], $row['name'], $row['phone']);
        $comment = new Comment($row['comment_id'], $row['author_id'], $pictureId, $row['comment_text'], $row['date']);
        $userAndComment[] = $user;
        $userAndComment[] = $comment;
        $result[] = $userAndComment;
    }
    
    return result;
}

// Saves a picture to the database. Returns true if successful or false otherwise.
function savePicture($albumId, $fileName, $title, $description, $dateAdded){
    $PDO = Connect();
    $sql = "INSERT INTO Picture (album_id, file_name, title, description, date_added)"
            . " VALUES( :albumId, :fileName, :title, :description, :dateAdded)";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['albumId' => $albumId, 'fileName' => $fileName, 'title' => $title,
        'description' => $description, 'dateAdded' => $dateAdded]);
    
    return $success;
}

// Deletes a pictures comments and then deletes the photo.
function deletePicture($pictureId){
    $PDO = Connect();
    $sql = "DELETE FROM Comment WHERE picture_id = :pictureId";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['pictureId' => $pictureId]);
    if($success){
        $sql1 = "DELETE FROM Picture WHERE picture_id = :pictureId";
        $preparedStatement2 = $PDO->prepare($sql1);
        $success = $preparedStatement2->execute(['pictureId' => $pictureId]);
    }
    
    // EVENTUALLY ADD CODE TO ACTUALLY DELETE THE FILE
    
    return $success;
}

// Gets a list of picture objects given an album Id.
function getAlbumPictures($albumId){
    $pictures = [];
    $PDO = Connect();
    $sql = "SELECT picture_id, file_name, title, description, date_added FROM Picture WHERE album_id = :albumId";
    $preparedStatement = $PDO->prepare($sql);
    $preparedStatement->execute(['albumId' => $albumId]);
    foreach($preparedStatement as $row){
        $picture = new Picture($row['picture_id'], $albumId, $row['file_name'], $row['Title'], $row['description'], $row['date_added']);
        $pictures[] = $picture;
    }
    
    return $pictures;
}

// Creates a friend request. Returns true if request was successful or false otherwise.
function createFriendRequest($userId, $requesteeId){
    $PDO = Connect();
    $sql = "INSERT INTO Friendship VALUES( :userId, :requesteeId, 'request')";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['userId' => $userId, 'requesteeId' => $requesteeId]);
    
    return $success;
}

// Accepts a friend request. Returns true if accept was successful or false otherwise.
function acceptFriendRequest($userId, $requesterId){
    $PDO = Connect();
    $sql = "UPDATE Friendship SET Status = 'accepted' WHERE friend_requester_id = :requesterId AND friend_requestee_id = :userId";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['userId' => $userId, 'requesterId' => $requesterId]);
    
    return $success;
}

// Deletes a current friend of a user. Returns true if delete was successful or false otherwise.
function deleteFriend($userId, $friendId){
    $PDO = Connect();
    $sql = "DELETE FROM Friendship "
                . "WHERE ((friend_requester_id = :userId AND friend_requestee_id= :friendId) "
                . "  OR (friend_requester_id = :friendId AND friend_requestee_id= :userId)) "
                . "    AND Status='accepted'";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['userId' => $userId, 'friendId' => $friendId]);
    
    return $success;
}

// Denies a friend request. Returns true if deny was success or false otherwise.
function denyFriendRequest($userId, $requesterId){
    $PDO = Connect();
    $sql =  "DELETE FROME Friendship WHERE friend_requester_id = :requesterId AND friend_requestee_id = :userId AND Status='request'";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['userId' => $userId, 'requesterId' => $requesterId]);
    
    return $success;
}

// Returns a list of user objects which are a given user's friends.
function getAllFriends($userId){
    $friends = [];
    $PDO = Connect();
    $sql = "SELECT friend_requestee_id FROM Friendship "
                . "WHERE friend_requester_id = :userId AND status = 'accepted'";
    $preparedStatement = $PDO->prepare($sql);
    $preparedStatement->execute(['userId' => $userId]);
    foreach($preparedStatement as $row){
        $friendId = $row['Friend_Requestee_Id'];
        $user = getUserFromID($friendId);
        if(is_null($user) == false){
            $friends[] = $user;
        }
    }
    
    $sql1 = "SELECT friend_requester_id FROM Friendship "
                . "WHERE friend_requestee_id = :userId AND status = 'accepted'";
    $preparedStatement1 = $PDO->prepare($sql1);
    $preparedStatement1->execute(['userId' => $userId]);
    foreach($preparedStatement1 as $row){
        $friendId = $row['Friend_Requestee_Id'];
        $user = getUserFromID($friendId);
        if(is_null($user) == false){
            $friends[] = $user;
        }
    }
    
    return $friends;
}

// Gets a list of User objects who currently have requested the user as a friend.
function getAllFriendRequests($userId){
    $friendRequests = [];
    $PDO = Connect();
    $sql = "SELECT friend_requester_id FROM Friendship "
                . "WHERE friend_requestee_id = :userId AND status = 'request'";
    $preparedStatement = $PDO->prepare($sql);
    $preparedStatement->execute(['userId' => $userId]);
    foreach($preparedStatement as $row){
        $friendId = $row['Friend_RequesterId'];
        $friendRequester = getUserFromID($friendId);
        $friendRequests[] = $friendRequester;
    }
    
    return $friendRequests;
}

?>


                