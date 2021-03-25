<?php
    require "src/post/sort.php";
    //if user is logged in load all posts 
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["currentUserId"])) {
        require "src/post/deletePost.php";
        require "src/post/likes.php";
        $postArray = $db->getAllPosts($sortBy, false);
        
    } 
    //else load public posts only
    else {
        $postArray = $db->getAllPosts($sortBy, true);
    }
    Post::printPostArray($postArray, $db, $sortBy);
?>