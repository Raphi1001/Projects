<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["currentUserId"])) {
    require "src/post/sort.php";
    require "src/post/deletePost.php";
    require "src/post/likes.php";
    $currUserId = $_SESSION["currentUserId"];
    $currUser = $db->getUserById($currUserId);
    $showPosts = false;

    //show edit Post Form
    if (isset($_GET['postid']) && is_numeric($_GET['postid']) && $newPost = $db->getPostbyId($_GET['postid'])) {
        //only owner and admins can edit the post
        if ($newPost->getProperty('userId') == $currUserId || $db->getAdminStatus($currUser->getProperty('uname'))) {
            $_SESSION['editPost'] = true;
            $_SESSION['newPost'] = serialize($newPost);
            require "src/post/newPost.php";
        } else {
        //redirect to index.php
?>
        <script type="text/javascript">
            window.location.href = 'index.php';
        </script>
<?php
        }
    } 
    //show all posts of currentUser
    else {
        $showPosts = true;
    }
    
    if ($showPosts) {
        $postArray = $db->getUserPosts($currUserId, $sortBy, false);
        Post::printPostArray($postArray, $db, $sortBy);
    }
} else {
    require "templates/loginToContinue.php";
}
?>
