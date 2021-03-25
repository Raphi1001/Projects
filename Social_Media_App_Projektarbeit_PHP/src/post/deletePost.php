<?php
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["currentUserId"]) && isset($_SESSION["currentUser"])) {
        if (isset($_GET['action']) && $_GET['action'] == 'deletePost' && isset($_GET['postid-del']) && is_numeric($_GET['postid-del'])) {
            $postid = $_GET['postid-del'];
            $currUserId = $_SESSION["currentUserId"];
            $currUser = $db->getUserById($currUserId);
            if ($currPost = $db->getPostById($postid)) {
                //only owner or admin can delete post
                if ($currPost->getProperty('userId') == $currUserId || $currUser->getProperty('isAdmin')) {
                   //if delete fails
                    if (!$db->deletePost($postid)) {
                        Alert::echoAlert('L&ouml;schen fehlgeschlagen!', false);
                    }
                }
            }
            unset($_GET['action']);
            unset($_GET['postid-del']);
        } 
    } 
?>