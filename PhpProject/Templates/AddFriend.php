<?php
    
require_once('../Functions/GeneralFunctions.php');
    
$pageTitle = "Add Friend";
    
$_SESSION["lastPage"] = "AddFriend";

$currentUser = getUserFromID($_SESSION['userLogged']);
$userId = $currentUser->getUserId();
$name = $currentUser->getName();
    
requireLogin();


$friendIdTxt = htmlspecialchars($_POST["friendIdTxt"]);
$errorMessage = ""; 
$successMessage = ""; 
$_SESSION['friendIdTxt'] = htmlspecialchars($_POST['friendIdTxt']);

//validators
if(isset($_POST['sendFriendRequest']))
    $friend = getUserFromID($friendIdTxt);
    if($friendIdTxt == $userId){
        $errorMessage = "You may not send a friend request to yourself!";
    } else if($friend == null){
        $errorMessage = "User is not in this social media yet!";
    } else {
        //first, check if already friends.
        $friendship = getFriendshipStatus($userId, $friendIdTxt);
        if($friendship != null){
            $status = $friendship->getStatus();
            if($status == "accepted"){
                $errorMessage = "This user is already your friend!";
            } else if ($status == "request"){
                // check if user is requester or requestee.
                // if user is requester, error: request already sent
                // otherwise, we can accept the existing friend request.
                if($userId == $friendship->getFriendRequesterId()){
                    $errorMessage = "You can't send this request twice. Invitation is still pending";
                } else if ($userId == $friendship->getFriendRequesteeId()){
                    acceptFriendRequest($userId, $friendIdTxt);
                    $successMessage = "You and " . $friend->getName() . " are now friends!";
                }
            }
        } else {
            createFriendRequest($userId, $friendIdTxt);
            $successMessage = "Your request was sent to ". $friend->getName() . " (ID: " . $friend->getUserId() . "). "
                        . "<br>" . "&nbsp &nbsp &nbsp" ."Once " . $friend->getName() . " accepts your request, you and ". $friend->getName() . " will be friends "
                        . "and will be able to see each others' shared albums.";
        }
        
        
    }
}        
include(COMMON_PATH . '\Header.php');

?>
<div class='container'> 
    <h1>Add Friend</h1>  
    <p>Welcome <b><?php print $name;?></b>! (Not you? Change your user <a href="Login.php">here</a>)</p>
    <hr>
    <p class="text-muted">Enter the ID of the user you want to be friends with:</p>

    <form method='post' action="">             
    <br><br><div class="row">
        <div class="col-lg-1" >
            <label for='friendId' class='col-form-label'><b>ID:</b> </label>
        </div>
        <div class="col-lg-3" >
            <input type='text' class='form-control' id='friendIdTxt' name='friendIdTxt' value='<?php print $_SESSION['friendIdTxt']; ?>' >
        </div> 
        <div class="col-lg-5" >
            <button type='submit' name='sendFriendRequest' class='btn btn-primary'>Send Friend Request</button>
        </div>
        <br><div class='col-lg-10'><p class='success'><?php print $successMessage; ?></p><p class='error'><?php print $errorMessage; ?></p></div>
    </div>
    </form>
</div>
<?php include(COMMON_PATH . '\Footer.php'); ?>


