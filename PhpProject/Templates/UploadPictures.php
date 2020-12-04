<?php 

require_once('../Functions/GeneralFunctions.php');

$pageTitle = "Upload Pictures";

$_SESSION["lastPage"] = "UploadPictures";

requireLogin();

include(COMMON_PATH . '\Header.php'); 
	    
//session_start();
include COMMON_PATH . '\ImageHandler.php';
	    //include_once COMMON_PATH . '\Settings.php';
	

	    //only authenticated users can access this page. Others are redirected to the login page
	    //updates the session so the user can come back to this page after authentication
//	    if ($_SESSION['userLogged'] == null)
//	    { 
//	        $_SESSION['activePage'] = "UploadPictures.php";        
//	        exit(header('Location: Login.php'));
//	    }
$userId  = $_SESSION['userLogged'];
$user = getUserFromID($userId);
//
//	    $dbConnection = parse_ini_file("../DatabaseInfo/db.ini");        	
//	    extract($dbConnection);
//	    $myPdo = new PDO($dsn, $user, $password);
//	    //to throw error messages to the user
//	    $myPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//	
$userAlbums = getAllUserAlbums($userId);

$userHasAlbums = (count($userAlbums) > 0) ? true : false;

$pictureTitle = $_POST['pictureTitle'] ?? "";
$pictureDescription = $_POST['pictureDescription'] ?? "";


if($userHasAlbums){

    if(isPostRequest()){
        if(isset($_POST['uploadAlbum'])){

            // set selected album in SESSSION['albumSelected']
            //check if there is an album
            if($_POST['uploadAlbum'] == ""){
                header('Refresh: 0');
            }

            $albumId = $_POST['uploadAlbum'];
            $files = $_POST['fileUpload'];

            $date = date("Y/m/d");
            $fileError = ValidateFileUpload($_FILES, 'fileUpload');

            if ($fileError == ""){ //files are valid to upload
                //$total = count($_FILES['fileUpload']['name']);
                $total = count($_FILES);
                //inserts picture reference in DB
                
//                $iSql = "INSERT INTO picture(album_id, fileName, title, description, date_added) "
//                       ."VALUES(:albumId, :fileName, :title, :description, :date_added)";
//
//                $uSql = "UPDATE album set date_updated = :dateUpdated WHERE album_id = :albumId";


                try{ 
                    for ($i=0; $i < $total; $i++) {
                        //gets file extension
                        
                        $pathinfo = pathinfo($_FILES['fileUpload']['name'][$i]);
                        //get picture_id that was saved in DB to use as file name (to avoid overwriting files)
                        $fileId = getLastPictureId() + 1;
                        $fileName = $fileId . '.' . $pathinfo['extension'];
                        
                        savePicture($albumId, $fileName, $pictureTitle, $pictureDescription, $date);
                        updateAlbum($albumId, $date);

                        
                        // CONTINUE FROM HERE
                        $filePath = save_uploaded_file(ORIGINAL_PICTURES_DIR, $_FILES['fileUpload'], $i, $pic_id);

                        $imageDetails = getimagesize($filePath);

                        if ($imageDetails && in_array($imageDetails[2], $supportedImageTypes)){
                            resamplePicture($filePath, ALBUM_PICTURES_DIR, IMAGE_MAX_WIDTH, IMAGE_MAX_HEIGHT);

                            //resamplePicture($filePath, ALBUM_THUMBNAILS_DIR, THUMB_MAX_WIDTH, THUMB_MAX_HEIGHT);


                            $pStmt = $myPdo->prepare($uSql);
                            $pStmt->execute(array(
                                ':albumId' => $uploadAlbum,
                                ':dateUpdated' => $date));
                        } else {
                            $error = "Uploaded file is not a supported type"; 
                            unlink($filePath);
                            $pStmt->rollback;
                        }
                        $pStmt->commit;
                    }
                } catch(PDOException $e) {
                    $fileError = $e->getMessage();
                }
                exit(header('Location: UploadPictures.php'));
            }
        }
    }
}
	

	?>
	    <div class="container">
	        <h1>Upload Pictures</h1>
                <hr>
	        <p class='text-muted'>Accepted picture types: JPG(JPEG), GIF and PNG.</p>
	        <p class='text-muted'>You can upload multiple pictures at a time by pressing the SHIFT key while selecting pictures.</p>
	        <p class='text-muted'>When uploading multiple pictures, the title and description fields will be applied to all pictures.</p>
                
                <?php if ($userHasAlbums) { ?>
                
                
	        <form action=UploadPictures.php method="post" enctype="multipart/form-data">
	            <div class='form-group row'>
	                <div class='col-lg-2 col-md-2 col-sm-3 col-xs-3'>
	                    <label for='uploadAlbum' class='col-form-label'><b>Upload To Album:</b> </label>
	                </div>
	                <div class='col-lg-4 col-md-6 col-sm-8 col-xs-8'>
	                    <select name='uploadAlbum' class='form-control'>
                                <?php foreach($userAlbums as $a) { ?>
                                <option value="
                                <?php echo $a->getAlbumId(); ?>"
                                <?php if($a->getAlbumId() == $_SESSION['albumSelected']) { echo "selected"; } ?>
                                ><?php echo $a->getTitle() ?>
                                </option>
                                <?php } ?>
	                    </select>
	                </div>
	                <div class='col-lg-4 col-md-2 col-sm-4' style='color:red'>
	                    <?php echo $uploadAlbumError; ?>
	                </div>
	            </div> 
	        
	            <div class='form-group row'>
	                <div class='col-lg-2 col-md-2 col-sm-3 col-xs-3'>
	                    <label for='fileUpload' class='col-form-label'><b>File to Upload:</b> </label>
	                </div>
	                <div class='col-lg-4 col-md-6 col-sm-8 col-xs-8 '>
	                    <input type='file' id='fileUpload' name='fileUpload[]' accept="image/gif, image/jpeg, image/png" multiple="multiple">
	                </div>
	                <div class='col-lg-4 col-md-2 col-sm-4' style='color:red'>
	                    <?php echo $fileError; ?>
	                </div>
	            </div> 
	            
	            <div class='form-group row'>
	                <div class='col-lg-2 col-md-2 col-sm-3 col-xs-3'>
	                    <label for='pictureTitle' class='col-form-label'><b>Title:</b> </label>
	                </div>
	                <div class='col-lg-4 col-md-6 col-sm-8 col-xs-8'>
	                    <input type='text' class='form-control' id='pictureTitle' name='pictureTitle' 
	                    value=<?php if (isset($_POST['pictureTitle'])){
	                            echo $_POST['pictureTitle'];} ?> >
	                </div>            
	            </div> 
	            
	            <div class='form-group row'>
	                <div class='col-lg-2 col-md-2 col-sm-3 col-xs-3'>
	                    <label for='pictureDescription' class='col-form-label'><b>Description:</b> </label>
	                </div>
	                <div class='col-lg-4 col-md-6 col-sm-8 col-xs-8'>
	                    <textarea  class='form-control' id='pictureDescription'  name='pictureDescription' style='height:150px'><?php
	                        $pictureDescription
	                    ?></textarea></div>
	            </div>
	            <br> 
	        
	            <div class='row'>
	                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3"></div>
	                <div class='col-lg-2 col-md-2 col-sm-2 col-xs-4 text-left'>
	                    <button type='submit' name='submit' class='btn btn-block btn-primary'>Submit</button>
	                </div>
	                <div class='col-lg-2 col-md-2 col-sm-2 col-xs-4 text-left'>
                            <a href="<?php echo TEMPLATES_URL . '/UploadPictures.php' ?>"<button type='reset' name='clear' class='btn btn-block btn-primary'>Clear</button></a>
	                </div>
	            </div>
	        </form>
                <?php } else { ?>
                <br>
                <br>
                <p> You have no albums! Click <a href='AddAlbum.php'>here</a> to create one!</p>
                <?php } ?>
                
	    </div>

<?php include(COMMON_PATH . '\Footer.php'); ?>
	

