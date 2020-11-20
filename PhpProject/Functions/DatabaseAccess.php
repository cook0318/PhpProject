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
    $sql = "SELECT Accessibility_Code, Description FROM Accessibility";
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
    $sql = "SELECT Album_Id, Title, Description, Date_Updated, Accessibility_Code "
                . "FROM Album  WHERE Owner_Id = :userId";
    $preparedStatement = $PDO->prepare($sql);
    $preparedStatement->execute(['userId' => $userId]);
    foreach($preparedStatement as $row){
        $album = new Album($row['album_id'], $row['title'], $row['description'], 
                $row['date_updated'], $row['owner_id'], $row['accessibility_code']);
        $albums[] = $album;
    }
    
    return $albums;
}

// Saves an Album object. Returns true if the save was successful, and false if
// the save was unsuccessful.
// Because AlbumId is auto-generated in the table, pass the album object with any 
// album ID such as 0, -1, or null.
function saveAlbum($album){
    $title = $album->getTitle();
    $description = $album->getDescription();
    $ownerId = $album->getOwnerId();
    $dateUpdated = $album->getDateUpdated();
    $accessibilityCode = $album->getAccessibilityCode();
    
    $PDO = Connect();
    $sql = "INSERT INTO Album (Title, Description, Owner_Id, Date_Updated, Accessibility_Code) "
            . "VALUES( :title, :description, :userId, :dateUpdated, :accessibilityCode)";
    $preparedStatement = $PDO->prepare($sql);
    $success = $preparedStatement->execute(['title' => $title, 'description' => $description,
        'userId' => $ownerId, 'dateUpdated' => $dateUpdated, 'accessibilityCode' => $accessibilityCode]);
    
    return $success;
}


?>





//Update an album's accessibility

	$sql = "UPDATE Album SET Accessibility_Code = :accessibilityCode WHERE Album_Id = :albumId";

//Get an user by id and password

	$sql = "SELECT UserId, Name, Phone FROM User WHERE UserId = :userId AND Password = :password";

//Get an user by id

	$sql = "SELECT UserId, Name, Phone FROM User WHERE UserId = :userId";

//Save an user

	$sql = "INSERT INTO User VALUES( :userId, :name, :phone, :password)";

//Save a comment

	$sql = "INSERT INTO Comment VALUES(null, :authorId, :pictureId, :comentText, :date)";

//Get comments and their authors for a picture

	$sql = "SELECT Comment_Id, Comment_Text, Date, UserId, Name, Phone FROM Comment "
                . "INNER JOIN User ON Comment.Author_Id = User.UserId WHERE Picture_Id = :pictureId";
				
//Save a picture
	
	$sql = "INSERT INTO Picture (Album_Id, File_Name, Title, Description, Date_Added) VALUES( :albumId, :fileName, :title, :description, :dateAdded)";
        
//Delete a picture and all its comments. A picture's comments must be deleted before delete the picture.

	$sql = "DELETE FROM Comment WHERE Picture_Id = :pictureId";
	$sql1 = "DELETE FROM Picture WHERE Picture_Id = :pictureId";
	
//Save a picture
	
	$sql = "INSERT INTO Picture (Album_Id, File_Name, Title, Description, Date_Added) VALUES( :albumId, :fileName, :title, :description, :dateAdded)";
        
//Get pictures of an album

	$sql = "SELECT Picture_Id, File_Name, Title, Description, Date_Added FROM Picture WHERE Album_Id = :albumId";
        
//Save a friend request

	$sql = "INSERT INTO Friendship VALUES( :userId, :requesteeId, 'request')";
	
//Accept a friend request

	$sql = "UPDATE Friendship SET Status = 'accepted' WHERE Friend_RequesterId = :requesterId AND Friend_RequesteeId = :userId";

//Delete a friend of a user:

	$sql = "DELETE FROM Friendship "
                . "WHERE ((Friend_RequesterId = :userId AND Friend_RequesteeId= :friendId) "
                . "  OR (Friend_RequesterId = :friendId AND Friend_RequesteeId= :userId)) "
                . "    AND Status='accepted'";	
				
//Deny a friend request

	$sql = "DELETE FROME Friendship WHERE Friend_RequesterId = :requesterId AND Friend_RequesteeId = :userId AND Status='request'";
	
//Get friends for a user. The first query returns all friends to whom the user initiated the requests.
//The second query returns all friends whose requests the user accepted. Add the results of the following two queries

	$sql = "SELECT Friend_RequesteeId FROM Friendship "
                . "WHERE Friend_RequesterId = :userId AND Status = 'accepted'";
				
	$sql = "SELECT Friend_RequesterId FROM Friendship "
                . "WHERE Friend_RequesteeId = :userId AND Status = 'accepted'";
				
//Get friend requesters of a user

	$sql = "SELECT Friend_RequesterId FROM Friendship "
                . "WHERE Friend_RequesteeId = :userId AND Status = 'request'";
				