# PHP Project for CST 8257

- See /Programming Resources for the Get Good at Git file.

- If you ever get this error, just continue: 

    - *warning: LF will be replaced by CRLF in FILE_NAME*
    - *The file will have its original line endings in your working directory*

- If you pull this repo, check out your branch, and try to change something and experience this message:
    - (after writing *git add -A*) **Error: PhpProject/ does not have a commit checked out fatal: adding files found**
    - try going one folder deeper, and deleting the hidden .git file, and retrying *git add -A*
    - For example my folder structure is: 


````
C:/Program Files/Ampps/www/CST8257/PhpProject/
                                    \_.git
                                    \_PhpProject
                                        \_.git      <- delete
                                        \_nbProject
                                        \_ProgrammingResources
                                        \_ReadMe.md
                                        ...
````


## To Do

 ````

    \_PhpProject
        \_nbProject
        \_ProgrammingResources
        \_Classes                       
            \_User.php
            \_Album.php         
            \_Friendship.php
        \_Functions                     
            \_DataBaseAccess.php
            \_GeneralFunctions.php
            \_ValidationFunctions.php   (checking for input validation)    
        \_Common
            \_Header.php
            \_Footer.php
        \_Templates                     // html pages
            \_Login.php
            \_ViewAlbum.php
            \_Friendships.php
        \_Scripts                       // if we use JS
        \_CSS
        \_UserPhotos
            \_Photo1111.jpg
            \_Photo1112.jpg
        \_ReadMe.md
            
````


EVERTON
-Make the database 
-Design/copy Header and Footer
	-Ensure current page is not displayed or highlighted and disabled in Nav Bar
-Secure login/signup with hashed passwords
	-Ensure requested page is saved in session


1 Billy
-Create Classes 
-Adding album
-viewing own albums


2 Everton
-view friends photos
-viewing own photos
-comments


3 Oussama
-Photo upload page
-Fiendships/requests/myfriends








