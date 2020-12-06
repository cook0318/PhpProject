<?php 

require_once('../Functions/GeneralFunctions.php');

$pageTitle = "Upload Pictures";

$_SESSION["lastPage"] = "UploadPictures";

requireLogin();

include(COMMON_PATH . '\Header.php'); 

$userId  = $_SESSION['userLogged'];
$user = getUserFromID($userId);

$userAlbums = getAllUserAlbums($userId);
$userHasAlbums = (count($userAlbums) > 0) ? true : false;

$pictureTitle = $_POST['pictureTitle'] ?? "";
$pictureDescription = $_POST['pictureDescription'] ?? "";
$titleError = "";
$descriptionError = "";
$uploadSuccess = "";


if($userHasAlbums){
    if(isPostRequest()){
        if(isset($_POST['uploadAlbum'])){

            // set selected album in SESSSION['albumSelected']
            //check if there is an album
            if($_POST['uploadAlbum'] == ""){
                header('Refresh: 0');
            }

            $albumId = intval($_POST['uploadAlbum']);
            $files = $_POST['fileUpload'];

            $date = date("Y/m/d");
            $fileError = ValidateFileUpload($_FILES, 'fileUpload');
            $titleError = ValidatePictureTitle($pictureTitle);
            $descriptionError = ValidatePictureDescription($pictureDescription);
            $total = 0;
            if ($fileError == "" && $titleError == "" && $descriptionError == ""){ //files are valid to upload
            
                $total = count($_FILES['fileUpload']['name']);

                try{ 
                    for ($i=0; $i < $total; $i++) {
                        //get file extension
                        $extension = null;
                        $fileInfo = getimagesize($_FILES['fileUpload']['tmp_name'][$i]);
                        
                        if ($fileInfo[2] == IMAGETYPE_JPEG) 
                        {
                            $extension = "jpg";
                        } 
                        elseif ($fileInfo[2] == IMAGETYPE_PNG) 
                        {
                            $extension = "png";
                        } 
                        elseif ($fileInfo[2] == IMAGETYPE_GIF) 
                        {
                            $extension = "gif";
                        }
    
                        //get picture_id that was saved in DB to use as file name (to avoid overwriting files)
                        $fileId = getLastPictureId() + 1;
                        $fileName = $fileId . '.' . $extension;
                        
                        savePicture($albumId, $fileName, $pictureTitle, $pictureDescription, $date);
                        updateAlbum($albumId, $date);

                        
                        // CONTINUE FROM HERE
                        $filePath = save_uploaded_file(ORIGINAL_PICTURES_DIR, $_FILES['fileUpload'], $i, $fileName);

                        $imageDetails = getimagesize($filePath);

                        if ($imageDetails && in_array($imageDetails[2], $supportedImageTypes)){
                            resamplePicture($filePath, ALBUM_PICTURES_DIR, IMAGE_MAX_WIDTH, IMAGE_MAX_HEIGHT);
                            resamplePicture($filePath, ALBUM_THUMBNAILS_DIR, THUMB_MAX_WIDTH, THUMB_MAX_HEIGHT);
                        } else {
                            $error = "Uploaded file is not a supported type"; 
                            unlink($filePath);
                        }
                    }
                } catch(PDOException $e) {
                    $fileError = $e->getMessage();
                }
                $uploadSuccess = "Successfully uploaded " . $total . " photo(s) to " . getAlbumFromId($albumId)->getTitle() . ".";
            }
        }
    }
}
	

	?>
	    <div class="container">
	        <h1 class='m-0-p-10 m-b-10'>Upload Pictures</h1>
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
                        <div class='col-lg-4 col-md-2 col-sm-4' style='color:red'>
	                    <?php echo $titleError; ?>
	                </div>
	            </div> 
	            
	            <div class='form-group row'>
	                <div class='col-lg-2 col-md-2 col-sm-3 col-xs-3'>
	                    <label for='pictureDescription' class='col-form-label'><b>Description:</b> </label>
	                </div>
	                <div class='col-lg-4 col-md-6 col-sm-8 col-xs-8'>
	                    <textarea  class='form-control' id='pictureDescription'  name='pictureDescription' style='height:150px'><?php
	                        $pictureDescription
	                    ?></textarea>
                        </div>
                        <div class='col-lg-4 col-md-2 col-sm-4' style='color:red'>
	                    <?php echo $descriptionError; ?>
	                </div>
	            </div>
	            <br> 
                    
                    
                    <div class='row'>
	                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3"></div>
                        <div class='col-lg-4 col-md-4 col-sm-2 col-xs-4 text-center'><span class='success'><?php echo $uploadSuccess ?></span></div>
	            </div>
                    <br>
                    
                    
	        
	            <div class='row p-b-10'> 
	                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3"></div>
	                <div class='col-lg-2 col-md-2 col-sm-2 col-xs-4 text-left'>
	                    <button type='submit' name='submit' class='btn-sm btn-block btn-primary'>Submit</button>
	                </div>
	                <div class='col-lg-2 col-md-2 col-sm-2 col-xs-4 text-left'>
                            <a href="<?php echo TEMPLATES_URL . '/UploadPictures.php' ?>"><button type='button' name='clear' class='btn-sm btn-primary'>Clear</button></a>
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
	

