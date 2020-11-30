<!DOCTYPE html>
<html lang="en" style="position: relative; min-height: 100%;">
<head>
    <title><?php echo $pageTitle; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../CSS/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="../CSS/Site.css" rel="stylesheet" type="text/css"/>
</head>
<body style="padding-top: 50px; margin-bottom: 60px;">
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" style="padding: 10px" href="http://www.algonquincollege.com">
                    <img src="/AlgCommon/Contents/img/AC.png"
                    alt="Algonquin College" style="max-width:100%; max-height:100%;"/>
                </a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="<?php if($activePage == "Index") { echo "active disableLink"; } ?>"><a href="Index.php">Home</a></li>
                    <li class="<?php if($activePage == "MyFriends") { echo "active disableLink"; } ?>"><a href="MyFriends.php">My Friends</a></li>
                    <li class="<?php if($activePage == "MyAlbums") { echo "active disableLink"; } ?>"><a href="MyAlbums.php">My Albums</a></li>
                    <li class="<?php if($activePage == "MyPictures") { echo "active disableLink"; } ?>"><a href="MyPictures.php">My Pictures</a></li>
                    <li class="<?php if($activePage == "UploadPictures") { echo "active disableLink"; } ?>"><a href="UploadPictures.php">Upload Pictures</a></li>
                    <li class="<?php if($activePage == "Login") { echo "active disableLink"; } ?>"><a href="Login.php">Log In</a></li>
                </ul>
            </div>
        </div>
    </nav>
