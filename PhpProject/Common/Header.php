<!DOCTYPE html>
<html lang="en" style="position: relative; min-height: 100%;">
<head>
    <title><?php echo $pageTitle; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../CSS/Site.css" rel="stylesheet" type="text/css"/>
</head>
<body style="margin-bottom: 60px;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-fixed-top">
        <a class="navbar-brand" style="padding: 10px" href="http://www.algonquincollege.com">
            <img src="/AlgCommon/Contents/img/AC.png"
            alt="Algonquin College" style="max-width:100%; max-height:100%;"/>
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
            
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto"> <!-- was just nav before-->
                <li class="nav-item <?php if(strpos($activePage, "Index")) { echo "active"; } ?>"><a class="nav-link" href="Index.php">Home</a></li>
                <li class="nav-item <?php if(strpos($activePage, "MyFriends")) { echo "active"; } ?>"><a class="nav-link" href="MyFriends.php">My Friends</a></li>
                <li class="nav-item <?php if(strpos($activePage, "MyAlbums")) { echo "active"; } ?>"><a class="nav-link" href="MyAlbums.php">My Albums</a></li>
                <li class="nav-item <?php if(strpos($activePage, "MyPictures")) { echo "active"; } ?>"><a class="nav-link" href="MyPictures.php">My Pictures</a></li>
                <li class="nav-item <?php if(strpos($activePage, "UploadPictures")) { echo "active"; } ?>"><a class="nav-link" href="UploadPictures.php">Upload Pictures</a></li>
                <li class="nav-item <?php if(strpos($activePage, "Login")) { echo "active"; } ?>"><a class="nav-link" href="Login.php">Log In</a></li>
            </ul>
        </div>
    </nav>
