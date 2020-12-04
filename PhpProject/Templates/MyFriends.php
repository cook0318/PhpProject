<?php 

require_once('../Functions/GeneralFunctions.php');

$pageTitle = "My Friends";

$_SESSION["lastPage"] = "MyFriends";

requireLogin();

$currentUser = getUserFromID($_SESSION['userLogged']);
$userId = $currentUser->getUserId();
$name = $currentUser->getName();



include(COMMON_PATH . '\Header.php'); 

	    $validatorError = "";
	
            $friends = getAllFriends($userId);
            
	    //Defriend button:    
	    if(isset($_POST['defriendBtn'])){
	        if (isset($_POST['defriend'])){
	            foreach ($_POST['defriend'] as $friendID) //iterate and look for what was selected
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
	        <h1>My Friends</h1
	        <p>Welcome <b><?php print $name;?></b>! (Not you? Change user <a href="Login.php">here</a>)</p>
                <hr>
	        <form method='post' action=MyFriends.php> 
	            <!--First table: FRIENDS-->
	            <table class="myFriendsTable table centerThirdColumn">
	            <!-- display table header -->
	            <thead>
	                <tr>
	                    <th scope="col">Friends:</th>
	                    <th scope="col"></th>
	                    <th scope="col"><a href="AddFriend.php">Add Friends</a></th>                                                                             
	                </tr>
	                <tr>
	                    <th scope="col">Name</th>
	                    <th scope="col">Shared Albums</th>
	                    <th scope="col">Defriend</th>                                                                             
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
                        echo "<td scope='col'><a href='FriendPictures.php?id=".$friendId."'>".$friend->getName()."</a></td>"; // Name
                        echo "<td scope='col'>".$counter."</td>"; // Shared albums
                        echo "<td scope='col'><input type='checkbox' name='defriend[]' value='$friendId'/></td>"; // Defriend            
                        echo "</tr>";  
                    }
                    ?>              
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <button type='submit' name='defriendBtn' class='btn btn-danger' onclick='return confirm("The selected friend will be defriended!")'>Defriend Selected</button>  
                            </td>
                        </tr>
                        
                    </tbody>
                    </table>
	  
	

                    <!--Second table: REQUESTS -->
	            <table class="myFriendsTable table centerSecondColumn">
	            <!-- display table header -->
	            <thead>
	                <tr>
	                    <th scope="col">Friend Requests:</th>
	                    <th scope="col"></th>                                                                             
	                </tr>
	                <tr>
	                    <th scope="col">Name</th>
	                    <th scope="col">Accept or Deny</th>                                                                             
	                </tr>
	            </thead>               
	            <!--example for table body - MUST BE TWEAKED TO BRING VALUES FROM DATABASE -->             
	            <tbody>
	            <?php
	            //getting a list of userId's where friendshipstatus = requested
                    $friendRequests = getAllFriendRequests($userId);
	            foreach ($friendRequests as $friendRequest)
	            {
	                echo "<tr>";
	                echo "<td scope='col'>".$friendRequest->getName()."</td>"; // Name
	                echo "<td scope='col'><input type='checkbox' name='acceptDeny[]' value='".$friendRequest->getUserId()."' /></td>"; // Accept or deny            
	                echo "</tr>";
	            }            
	            ?>   
                        <tr>
                            <td></td>
                            <td><!--Accept/Deny buttons--> 
                                <button type='submit' name='acceptBtn' class='btn btn-primary'>Accept Selected</button>  
                                <button type='submit' name='denyBtn' class='btn btn-danger ' onclick='return confirm("The selected request will be denied!")'>Deny Selected</button>
                            </td>
                        </tr>
	            </tbody>
                    </table>
                </form> 
            </div>

<?php include(COMMON_PATH . '\Footer.php'); ?>
	