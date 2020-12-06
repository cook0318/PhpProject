<?php

// Functions requiring database access.

foreach (glob("../Classes/*.php") as $filename)
{
    require_once($filename);
}

$dbConnection = null;

// The first time this function is called, it creates and returns a PDO object. It then
// sets the global variable $dbConnection to this object, so all subsequent connections
// do not need to create a new PDO each time.
function Connect(){
    global $dbConnection;
    if(is_null($dbConnection)){
        $connectionInfo = parse_ini_file('../DatabaseInfo/db.ini');
        extract($connectionInfo);
        $dbConnection = new PDO($dsn, $user, $password);
    }
    return $dbConnection;
}

// Escapes Special characters and white spaces.
function idEscape ($str) {
    $str = str_ireplace(' ', '', $str);
    return htmlspecialchars($str);
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

// Returns TRUE and save User ID in Session["userLogged"] if LogIn is successful. Returns FALSE otherwise.
function logIn($id, $password){
    $user = null;
    $PDO = Connect();
    $sql = "SELECT * FROM `user` WHERE `userId` = :userId";
    $preparedStatement = $PDO->prepare($sql);
    $preparedStatement->execute(['userId' => idEscape($id)]);
    $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
    if(password_verify($password, $row["password"])){
        $_SESSION["userLogged"] = $row["userId"];
        return true;
    } else {
        return false;
    }
}

// Saves a user to the database. Returns true if save successful, otherwise false.
function createUser($id, $name, $phone, $password){
    $PDO = Connect();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO `user` (`userId`, `name`, `phone`, `password`) VALUES (?, ?, ?, ?)";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute([idEscape($id), $name, $phone, $hashedPassword]);
    if($success) { $_SESSION["userLogged"] = idEscape($id); }

    return $success;
}

// Returns a user object given an ID, or returns null if the ID does not exist.
function getUserFromID($id){
    $user = null;
    $PDO = Connect();
    $sql = "SELECT userId, name, phone FROM User WHERE userId = :userId";
    $preparedStatement = $PDO->prepare($sql);
    if($preparedStatement->execute(['userId' => idEscape($id)])){
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        if($row != 0){
            $user = new User($row['userId'], $row['name'], $row['phone']);
        }
    }
    
    return $user;
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

// gets all comments on a photo and returns an array of comment objects.
function getComments($pictureId){
    $comments = [];
    $PDO = Connect();
    $sql = "SELECT *, DATE(date) as `onlydate` FROM comment WHERE picture_id = :pictureId ORDER BY date DESC";
    $preparedStatement = $PDO -> prepare($sql);
    $preparedStatement->execute(['pictureId' => $pictureId]);
    foreach($preparedStatement as $row){
        $comment = new Comment($row['comment_id'], $row['author_id'], $pictureId, $row['comment_text'], $row['onlydate']);
        $comments[] = $comment;
    }    
    return $comments;
}

// Saves a picture to the database. Returns true if successful or false otherwise.
function savePicture($albumId, $fileName, $title, $description, $dateAdded){
    $PDO = Connect();
    $sql = "INSERT INTO Picture VALUES(null, :albumId, :fileName, :title, :description, :dateAdded)";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['albumId' => $albumId, 'fileName' => $fileName, 'title' => $title, 'description' => $description, 'dateAdded' => $dateAdded]);
    
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
    
    return $success;
}

// Gets a list of picture objects given an album Id.
function getAlbumPictures($albumId){
    $pictures = [];
    $PDO = Connect();
    $sql = "SELECT picture_id, fileName, title, description, date_added FROM Picture WHERE album_id = :albumId";
    $preparedStatement = $PDO->prepare($sql);
    $preparedStatement->execute(['albumId' => $albumId]);
    foreach($preparedStatement as $row){
        $picture = new Picture($row['picture_id'], $albumId, $row['fileName'], $row['title'], $row['description'], $row['date_added']);
        $pictures[] = $picture;
    }
    
    return $pictures;
}

// returns the album object for a given ID.
function getAlbumFromId($albumId){
    $album = "";
    $PDO = Connect();
    $sql = "SELECT album_id, title, description, date_updated, accessibility_code, "
                . "owner_id FROM Album  WHERE album_id = :albumId";
    $preparedStatement = $PDO->prepare($sql);
    if($preparedStatement->execute(['albumId' => $albumId])){
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        $album = new Album($row['album_id'], $row['title'], $row['description'], 
                $row['date_updated'], $row['owner_id'], $row['accessibility_code']);
    }
    
    return $album;
}

// Deletes all photos in an album, deletes photos from filepaths, then deletes the album itself.
// Returns true if successful, otherwise returns false.
function deleteAlbum($albumId){
    $pictures = getAlbumPictures($albumId);
    foreach($pictures as $picture){
        $success = deletePicture($picture->getPictureId());
        if($success == false){
            return false;
        }
        
        unlink($picture->getAlbumFilePath());
        unlink($picture->getThumbnailFilePath());
        unlink($picture->getOriginalFilePath());
    }
    $PDO = Connect();
    $sql =  "DELETE FROM album WHERE album_id = :albumId";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['albumId' => $albumId]);
    
    return $success;
}

//updates album's dateUpdated with a new date.
function updateAlbum($albumId, $date){
    $PDO = Connect();
    $sql = "UPDATE album set date_updated = :dateUpdated WHERE album_id = :albumId";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['albumId' => $albumId,'dateUpdated' => $date]);
    
    return $success;
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
    $sql = "UPDATE Friendship SET Status = 'accepted' WHERE friend_requesterId = :requesterId AND friend_requesteeId = :userId";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['userId' => $userId, 'requesterId' => $requesterId]);
    
    return $success;
}

// Deletes a current friend of a user. Returns true if delete was successful or false otherwise.
function deleteFriend($userId, $friendId){
    $PDO = Connect();
    $sql = "DELETE FROM Friendship "
                . "WHERE ((friend_requesterId = :userId AND friend_requesteeId= :friendId) "
                . "  OR (friend_requesterId = :friendId AND friend_requesteeId= :userId)) "
                . "    AND Status='accepted'";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['userId' => $userId, 'friendId' => $friendId]);
    
    return $success;
}

// Denies a friend request. Returns true if deny was success or false otherwise.
function denyFriendRequest($userId, $requesterId){
    $PDO = Connect();
    $sql =  "DELETE FROM Friendship WHERE friend_requesterId = :requesterId AND friend_requesteeId = :userId AND Status='request'";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['userId' => $userId, 'requesterId' => $requesterId]);
    
    return $success;
}

// Returns a list of user objects which are a given user's friends.
function getAllFriends($userId){
    $friends = [];
    $PDO = Connect();
    $sql = "SELECT friend_requesteeId FROM Friendship "
                . "WHERE friend_requesterId = :userId AND status = 'accepted'";
    $preparedStatement = $PDO->prepare($sql);
    $preparedStatement->execute(['userId' => $userId]);
    foreach($preparedStatement as $row){
        $friendId = $row['friend_requesteeId'];
        $user = getUserFromID($friendId);
        if(is_null($user) == false){
            $friends[] = $user;
        }
    }
    
    $sql1 = "SELECT friend_requesterId FROM Friendship "
                . "WHERE friend_requesteeId = :userId AND status = 'accepted'";
    $preparedStatement1 = $PDO->prepare($sql1);
    $preparedStatement1->execute(['userId' => $userId]);
    foreach($preparedStatement1 as $row){
        $friendId = $row['friend_requesterId'];
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
    $sql = "SELECT friend_requesterId FROM Friendship "
                . "WHERE friend_requesteeId = :userId AND status = 'request'";
    $preparedStatement = $PDO->prepare($sql);
    $preparedStatement->execute(['userId' => $userId]);
    foreach($preparedStatement as $row){
        $friendId = $row['friend_requesterId'];
        $friendRequester = getUserFromID($friendId);
        $friendRequests[] = $friendRequester;
    }
    
    return $friendRequests; 
}

// Gets a list of picture objects given an album Id.
function getPictureById($pictureId){
    $PDO = Connect();
    $sql = "SELECT * FROM Picture WHERE picture_id = :pictureId";
    $preparedStatement = $PDO->prepare($sql);
    if($preparedStatement->execute(['pictureId' => $pictureId])){
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        $picture = new Picture($row['picture_id'], $row['album_id'], $row['fileName'], $row['title'], $row['description'], $row['date_added']);
    }    
    return $picture;
}

// Gets friendship 
function getFriendshipStatus($userLoggedID, $friendID){
    $friendship = null;
    $PDO = Connect();
    $sql = "SELECT * FROM `friendship` WHERE (`friend_requesterId` = :userID and `friend_requesteeId` = :friendID) "
            . "OR (`friend_requesteeId` = :userID and `friend_requesterId` = :friendID)";
    $preparedStatement = $PDO->prepare($sql);
    if($preparedStatement->execute(['userID' => idEscape($userLoggedID), 'friendID' => idEscape($friendID)])){
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        if($row != 0){
            $friendship = new Friendship($row['friend_requesterId'], $row['friend_requesteeId'], $row['status']);
        }
    }
    
    return $friendship;
}

// returns the last added picture ID.
function getLastPictureId(){
    $lastId = 0;
    $PDO = Connect();
    $sql = "SELECT picture_id from Picture order by picture_id DESC limit 1";
    $preparedStatement = $PDO->prepare($sql);
    if($preparedStatement->execute()){
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        $lastId = $row['picture_id'];
    }    
    
    return $lastId;
}

// when a user accesses a friends albums, get the first shared album to make selected
// and to also check if they have any shared albums.
function getFirstSharedAlbumId($friendId){
    $albumId = 0;
    $PDO = Connect();
    $sql = "SELECT album_id from Album WHERE owner_id = :ownerId and accessibility_code = 'shared' order by album_id ASC limit 1";
    $preparedStatement = $PDO->prepare($sql);
    if($preparedStatement->execute(['ownerId' => $friendId])){
        $row = $preparedStatement->fetch(PDO::FETCH_ASSOC);
        $albumId = $row['album_id'];
    }    
    
    return $albumId;
}
?>