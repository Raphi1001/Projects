<?php
/* Delete tag */
if (isset($_GET["tagToDelete"])) {
    $deleteErr = $newPost->deleteTag($_GET["tagToDelete"]);
    if (!$deleteErr) {
        $_SESSION["newPost"] = serialize($newPost);
    } else {
        Alert::echoAlert($deleteErr, false);
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //if remove img form is submitted
    if (isset($_POST["removeImg"]) && $_POST["removeImg"] == true) {
            $newPost->setImg(NULL);
            $_SESSION["newPost"] = serialize($newPost);
    }
    //if tag-form is submitted
    else {

        if (!isset($_POST["postNewTag"])) {
            $_POST["postNewTag"] = "";
        }
        $tagErr = $newPost->addTag($_POST["postNewTag"]);

        /* Upload new Tag if no errors are found */
        if (!$tagErr) {
            $_SESSION["newPost"] = serialize($newPost);
        }
    }
}
?>