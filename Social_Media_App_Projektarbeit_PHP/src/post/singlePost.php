<?php
require "src/post/deletePost.php";
require "src/post/deleteComment.php";
require "src/post/newComment.php";
require "src/post/likes.php";
//edit comment
if (isset($_GET['action']) && $_GET['action'] == 'editComment') {
    require "src/post/editComment.php";
    unset($_GET['action']);
} 
//get post from DB and prints single post
else if (isset($_GET["postid"]) && $post = $db->getPostById($_GET["postid"])) {
    $postid = $_GET["postid"];
    $userid = $post->getProperty("userId");
    $username = $db->getUsername($userid);
    $user = $db->getUserById($userid);
    //if user has profil pic use this
    if ($userPic = $user->getProperty("userPicObj")) {
        $thumbProfilePic =  $userPic->getProperty("thumbPath");
        $pathProfilePic =  $userPic->getProperty("path");
    } 
    //else use default profil pic
    else {
        $thumbProfilePic =  "resources/default/profilePic.png";
        $pathProfilePic =  "resources/default/profilePic.png";
    }
    
    $pathPostPic = $post->getProperty("imgObject") ? $post->getProperty("imgObject")->getProperty("path") : "";

    $description = $post->getProperty("description");
    $tagArray = $db->getPostTags($postid);
    $time = $post->getTimeDifference();
    $likes = $post->getProperty("likes");
    $dislikes = $post->getProperty("dislikes");
    $privacyStatus = $post->getProperty("privacy");
    //parameters for comment input field
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        $currentUserid = $_SESSION["currentUserId"];
        $currentUname = $db->getUsername($currentUserid);
        $currentUser = $db->getUserById($currentUserid);
        if ($currUserPic = $currentUser->getProperty("userPicObj")) {
            $currUserProfilePic =  $currUserPic->getProperty("path");
            $currUserThumbProfilePic = $currUserPic->getProperty("thumbPath");
        } else {
            $currUserProfilePic = "resources/default/profilePic.png";
            $currUserThumbProfilePic = "resources/default/profilePic.png";
        }
    }
    include "templates/post/singlePostTemplate.php";
} else { ?>
    <script type="text/javascript">
        window.location.href = 'index.php';
    </script>
<?php } ?>