<?php
//if form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["newComment"]) && $_POST["newComment"] == "true") {
    unset($_GET['action']);
    $createComment = true;
    $currentUserId = $_SESSION["currentUserId"];
    $currentPostId = $_GET["postid"];
    $newComment = new Comment();

    $errArray = $newComment->createComment($currentUserId, $currentPostId, $_POST["message"]);
    
    //show error message if error occured
    foreach ($errArray as $errMsg) {
        if ($errMsg) {
            $createComment = false;
            break;
        }
    }
    //insert comment if no errors are found
    if ($createComment) {
        if ($db->insertComment($newComment)) {
            Alert::echoAlert("Ihr Kommentar wurde erstellt!", true);
        } else {
            $createComment = false;
            Alert::echoAlert("Ihr Kommentar konnte nicht erstellt werden!", false);
        }
    }
}
?>