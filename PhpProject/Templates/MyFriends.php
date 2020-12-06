<?php 

require_once('../Functions/GeneralFunctions.php');

$pageTitle = "My Friends";

$_SESSION["lastPage"] = "MyFriends";

requireLogin();

$currentUser = getUserFromID($_SESSION['userLogged']);
$userId = $currentUser->getUserId();
$name = $currentUser->getName();

$friends = getAllFriends($userId);
$friendRequests = getAllFriendRequests($userId);

include(COMMON_PATH . '\Header.php'); 

	    $validatorError = "";
	    //Defriend button:    
	    if(isset($_POST['defriendBtn'])){
	        if (isset($_POST['defriend'])){
	            foreach ($_POST['defriend'] as $friendId) //iterate and look for what was selected
	            {
	                //for each selected line, delete the corresponding friend from friends' list
                        deleteFriend($userId, $friendId);                
	            }
	            header('Location: MyFriends.php'); //redirect to update table view
	            exit;             
	        }
	        else 
	        {
	            $validatorError = "You must select at least one checkbox!"; //at least one checkbox must be selected
	        }          
	    }
            
	    //Accept Selected Button
	    if (isset($_POST['acceptBtn'])){
	        if (isset($_POST['acceptDeny'])){
	            foreach ($_POST['acceptDeny'] as $requesterId){
                        acceptFriendRequest($userId, $requesterId);                      
 	            }
	            header('Location: MyFriends.php'); //redirect to update table view
	            exit; 
	        }
	        else 
	        {
	            $validatorError = "You must select at least one checkbox!"; //at least one checkbox must be selected
	        }   
	    }
	    
	    //Deny Selected Button
	    if (isset($_POST['denyBtn'])){
	        if (isset($_POST['acceptDeny'])){
	            foreach ($_POST['acceptDeny'] as $requesterId){
	                //deny request(pending) statement from database
                        denyFriendRequest($userId, $requesterId);
	            }
	            header('Location: MyFriends.php'); //redirect to update table view
	            exit;            
	        }
	        else 
	        {
	            $validatorError = "You must select at least one checkbox!"; //at least one checkbox must be selected
	        }   
	    }

	?>
	    <div class="container">
	        <h1 class='m-0-p-10 m-b-10'>My Friends</h1
	        <p>Welcome <b><?php print $name;?></b>! (Not you? Change user <a href="Login.php">here</a>)</p>
                <hr>
	        <form method='post' action=MyFriends.php> 
                    
<?php if($friends != 0 && count($friends) != 0){ ?> 
	            <!--First table: FRIENDS-->
	            <table class="myFriendsTable table centerThirdColumn">
	            <!-- display table header -->
	            <thead>
	                <tr>
	                    <th scope="col w-20">Friends:</th>
	                    <th scope="col w-20"></th>
	                    <th scope="col w-60"><a href="AddFriend.php">Add Friends</a></th>                                                                             
	                </tr>
	                <tr>
	                    <th scope="col w-20">Name</th>
	                    <th scope="col w-20">Shared Albums</th>
	                    <th scope="col w-60">Defriend</th>                                                                             
	                </tr>
	            </thead>   
	

	            <!-- display table body -->             
	            <div class='col-lg-4' style='color:red'> <?php print $validatorError;?></div><br>
	            <tbody>
<?php   
                    foreach($friends as $friend){
                        $friendId = $friend->getUserId();
                        $albums = getAllUserAlbums($friendId);
                        $counter = 0;
                        foreach($albums as $album){
                            if($album->getAccessibilityCode() == "shared"){
                                $counter++;
                            }
                        }
                        echo "<tr>";
                        echo "<td scope='col' class='w-20'><a href='FriendPictures.php?id=".$friendId."'>".$friend->getName()."</a></td>"; // Name
                        echo "<td scope='col' class='w-20'>".$counter."</td>"; // Shared albums
                        echo "<td scope='col' class='w-60'><input type='checkbox' name='defriend[]' value='$friendId'/></td>"; // Defriend            
                        echo "</tr>";  
                    }
                    ?>              
                        <tr>
                            <td class='text-right' colspan="3">
                                <button class='btn-sm btn-danger' type='submit' name='defriendBtn' class='btn btn-danger' onclick='return confirm("The selected friend will be defriended!")'>Defriend Selected</button>  
                            </td>
                        </tr>
                        
                    </tbody>
                    </table>
<?php  } else { ?>
                    <p class='text-center'>You have no friends. Click <a href='AddFriend.php'>here</a> to add some!<p>            
<?php } ?>
	
<?php if($friendRequests != 0 && count($friendRequests) != 0){ ?> 
                    <!--Second table: REQUESTS -->
	            <table class="myFriendsTable table centerSecondColumn">
	            <!-- display table header -->
	            <thead>
	                <tr>
	                    <th scope="col" class='w-20'>Friend Requests:</th>
                            <th scope='col' class='w-20'></th>
	                    <th scope="col" class='w-60'></th>                                                                             
	                </tr>
	                <tr>
	                    <th scope="col" class='w-20'>Name</th>
                            <th scope='col' class='w-20'></th>
	                    <th scope="col" class='w-60 text-center'>Accept or Deny</th>                                                                             
	                </tr>
	            </thead>               
	            <!--example for table body - MUST BE TWEAKED TO BRING VALUES FROM DATABASE -->             
	            <tbody>
	            <?php
	            //getting a list of userId's where friendshipstatus = requested
                    
	            foreach ($friendRequests as $friendRequest)
	            {
	                echo "<tr>";
	                echo "<td scope='col' class='w-20'>".$friendRequest->getName()."</td>"; // Name
                        echo "<td scope='col' class='w-20'></td>";
	                echo "<td scope='col' class='w-60 text-center'><input type='checkbox' name='acceptDeny[]' value='".$friendRequest->getUserId()."' /></td>"; // Accept or deny            
	                echo "</tr>";
	            }            
	            ?>   
                        <tr>
                            <td class='text-right' colspan='3'><!--Accept/Deny buttons--> 
                                <button type='submit' name='acceptBtn' class='btn-sm btn-primary'>Accept Selected</button>  
                                <button type='submit' name='denyBtn' class='btn-sm btn-danger ' onclick='return confirm("The selected request will be denied!")'>Deny Selected</button>
                            </td>
                        </tr>
	            </tbody>
                    </table>
                </form> 
            </div>

<?php  } else { ?>
                    <br><br><p class='text-center'>You have no friend requests.<p>            
<?php } ?>


</div>

<?php include(COMMON_PATH . '\Footer.php'); ?>
	