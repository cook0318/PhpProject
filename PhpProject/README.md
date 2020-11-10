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

- Identify main entities/classes/parts of project: the "friendships" and how they work, "albums" and how they work, login security... etc
    - Split the project duties amongst ourselves
- Design database together (somebody can do the creation after the meeting, but at least type out the tables/entities/relationships together)
- Identify project folder structure. Here's a starting point but we can change anything:
 ````

    \_PhpProject
        \_nbProject
        \_ProgrammingResources
        \_Classes                       // would include class methods like Friendship.CheckIfFriends(user1,user2)
            \_User.php
            \_Album.php         
            \_Friendship.php
        \_Functions                     // for non-class related functions for generating HTML, doing simple logic
            \_DataBaseAccess.php
            \_GeneralFunctions.php      
        \_Templates                     // html pages
            \_Login.php
            \_ViewAlbum.php
            \_Friendships.php
        \_Scripts                       // if we use JS
        \_CSS
        \_UserPhotos
            \_User123AlbumABC
        \_ReadMe.md
            
````
- Set out coding standards
   - camelCase or PascalCase, for variables/classes/methods/functions?
- Figure out universal "things"
   - No idea how to explain this but for example the current user object will be stored in $_SESSION['CurrentUser'] and other stuff we will all use