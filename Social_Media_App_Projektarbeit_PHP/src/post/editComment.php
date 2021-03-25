<?php
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($_SESSION["currentUserId"]) && isset($_SESSION["currentUser"])) {
        if (isset($_GET['commentid-edit']) && is_numeric($_GET['commentid-edit'])) {
            $commentid = $_GET['commentid-edit'];
            $currUserId = $_SESSION["currentUserId"];
            $currUser = $db->getUserById($currUserId);
            //loads comment from database
            if ($currComment = $db->getCommentById($commentid)) {
                //only owner or admin can edit comment
                if ($currComment->getProperty('userId') == $currUserId || $currUser->getProperty('isAdmin')) {
                    $commentPostId = $currComment->getProperty("postId");
                    $commentUserid = $currComment->getProperty("userId");
                        $commentUser = $db->getUserById($commentUserid);
                        $commentUname = $commentUser->getProperty("uname");

                        //default profile pic if not set
                        if ($commentProfilePic = $commentUser->getProperty("userPicObj")) {
                            $commentUserThumbPic =  $commentProfilePic->getProperty("thumbPath");
                            $commentUserProfilePic =  $commentProfilePic->getProperty("path");
                        } else {
                            $commentUserThumbPic = "resources/default/profilePic.png";
                            $commentUserProfilePic = "resources/default/profilePic.png";
                        }
                        $commentMessage = $currComment->getProperty("message");
                        $commentId = $currComment->getProperty("commentId");
                        
                    //if form is submitted
                    if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['message-edit'])) {
                        $commentErr = $currComment->setComment($_POST['message-edit']);
                        
                        //edit comment
                        if (!$commentErr && $db->updateComment($currComment)) {
                            echo "<script type=\"text/javascript\">window.location.href=\"index.php?page=singlePost&postid=$commentPostId\";</script>";
                        //if edit comment fails
                        } else {
                            Alert::echoAlert('Bearbeitung fehlgeschlagen!', false);
                            require "templates/post/editCommentTemplate.php";
                        }
                    } else {
                        require "templates/post/editCommentTemplate.php";
                    }
                }
            }
            unset($_GET['commentid-edit']);
        } 
    } 
?>