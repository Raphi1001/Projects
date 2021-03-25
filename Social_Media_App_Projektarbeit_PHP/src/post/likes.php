<?php
    if (isset($_GET['postId_likes']) && isset($_GET['userId_likes']) && isset($_GET['action'])) {
        $postId_likes = $_GET["postId_likes"];
        $userId_likes = $_GET["userId_likes"];
        $unset = false;
        //only admins can remove likes and dislikes
        if (isset($_SESSION["currentUser"]) && $db->getAdminStatus($_SESSION["currentUser"])) {
            if ($_GET['action'] == "rmAllLikes") {
                $db->removeAllLikes($postId_likes);
                $unset = true;
            } else if ($_GET['action'] == "rmAllDislikes") {
                $db->removeAllDislikes($postId_likes);
                $unset = true;
            }
        }
        //perform correct action 
        switch($_GET['action']) {
            case 'like':
                $db->likePost($postId_likes, $userId_likes);
                $unset = true;
                break;
            case 'dislike':
                $db->dislikePost($postId_likes, $userId_likes);
                $unset = true;
                break;
            case 'removelike':
                $db->removeLike($postId_likes, $userId_likes);
                $unset = true;
                break;
            case 'removedislike':
                $db->removeDislike($postId_likes, $userId_likes);
                $unset = true;
                break;
        }
        //unset all $_GET paramaters
        if ($unset) {
            unset($_GET['action']);
            unset($_GET['postId_likes']);
            unset($_GET['userId_likes']);
        }
    }
?>