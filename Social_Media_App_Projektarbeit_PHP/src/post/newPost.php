<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    $currentUser = $_SESSION["currentUser"];
    $currentUserId = $_SESSION["currentUserId"];

    if (!isset($newPost)) {
        //create Post object
        $newPost = new Post();
    }
    //Check if newPost already exists 
    if (isset($_SESSION["newPost"])) {
        $newPost = unserialize($_SESSION["newPost"]);
    }

    $user = $db->getUser($currentUser);
    if ($userPic = $user->getProperty("userPicObj")) {
        $thumbProfilePic =  $userPic->getProperty("thumbPath");
        $pathProfilePic =  $userPic->getProperty("path");
    } else {
        $thumbProfilePic =  "resources/default/profilePic.png";
        $pathProfilePic =  "resources/default/profilePic.png";
    }


    if (isset($_GET["subpage"])) {
        $subpage = $_GET["subpage"];
    } else {
        $subpage = "";
    }
    switch ($subpage) {
            /* create a new post (reset values)*/
        case "reset":
            unset($_SESSION["newPost"]);
            $newPost = new Post();
            require "src/post/newPostCreate.php";
            require "templates/post/newPostCreateForm.php";
            break;
            /* Go Back to Main Menu and delete post */
        case "abort":
            unset($_SESSION["newPost"]);
?>
            <script type="text/javascript">
                window.location.href = 'index.php';
            </script>
            <?php
            break;
            /* Upload finished post to database */
        case "upload":
            if (isset($_SESSION['editPost']) && $_SESSION['editPost'] == true) {
                $result = $db->updatePost($newPost);
            } else {
                $result = $db->insertPost($newPost);
            }
            if ($result) {
                unset($_SESSION['editPost']);
                unset($_SESSION["newPost"]);
            ?>
                <script type="text/javascript">
                    window.location.href = 'index.php';
                </script>
<?php
            } else {
                Alert::echoAlert("Ein Fehler ist aufgetreten!", false);
                require "src/post/newPostPreview.php";
                require "templates/post/newPostPreviewPage.php";
            }
            break;
            /* Browse user uploads */
        case "uploads";
            require "src/user/uploads.php";
            break;
            /* Preview Post */
        case "preview":
            require "src/post/newPostPreview.php";
            require "templates/post/newPostPreviewPage.php";

            break;
            /* Create Post */
        default:
            require "src/post/newPostCreate.php";
            require "templates/post/newPostCreateForm.php";
    }
} else {
    require "templates/loginToContinue.php";
}
?>