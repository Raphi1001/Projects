<?php
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["currentUserId"]) && isset($_SESSION["currentUser"])) {
        if (isset($_GET['action']) && $_GET['action'] == 'deleteComment' && isset($_GET['commentid-del']) && is_numeric($_GET['commentid-del'])) {
            $commentid = $_GET['commentid-del'];
            $currUserId = $_SESSION["currentUserId"];
            $currUser = $db->getUserById($currUserId);
            if ($currComment = $db->getCommentById($commentid)) {
                //only owner or admin can delete comment
                if ($currComment->getProperty('userId') == $currUserId || $currUser->getProperty('isAdmin')) {
                    //delete successfull
                    if ($db->deleteComment($commentid)) {
                        Alert::echoAlert('Der Kommentar wurde gel&ouml;scht!', true);
                    //delete falis
                    } else {
                        Alert::echoAlert('L&ouml;schen fehlgeschlagen!', false);
                    }
                }
            }
            unset($_GET['action']);
            unset($_GET['commentid-del']);
        } 
    } 
?>