<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    if (isset($_SESSION["currentUser"])) $currentUser = $_SESSION["currentUser"];
    if (isset($_SESSION["currentUserId"])) $currentUserId = $_SESSION["currentUserId"];

    /* if a form is sumbitted */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // use selected file
        if (isset($_POST["useImg"])) {
            $fileToUse = new Img();
            $fileToUse->loadImage($_POST["useImg"], $currentUserId);

            //use file for new post
            if (isset($_GET["page"]) && $_GET["page"] == "newPost") {

                if (file_exists($fileToUse->getProperty("path"))) {
                    $newPost->setImg($fileToUse);
                    $_SESSION["newPost"] = serialize($newPost);
            ?>
                    <script type="text/javascript">
                        window.location.href = 'index.php?page=newPost&subpage=preview';
                    </script>
                <?php
                }
            }
            //use file as profil pic
            else {
                if (file_exists($fileToUse->getProperty("path"))) {
                    $userToUpdate = $db->getUser($currentUser);
                    $userToUpdate->setUserPicObj($fileToUse);
                    $db->updateUser($userToUpdate, $currentUser);
                }
                ?>
                <script type="text/javascript">
                    window.location.href = 'index.php?page=account';
                </script>
<?php
            }
        }

        //if delete file form is submitted 
        else if (isset($_POST["deleteImg"])) {
            //delete user Pic reference if file is user pic
            $currentUserObj = $db->getUser($currentUser);
            $userPicObj = $currentUserObj->getProperty("userPicObj");
            if (isset($userPicObj) && $_POST["deleteImg"] == $userPicObj->getProperty("fileName")) {
                $currentUserObj->setUserPicObj(NULL);
                $db->updateUser($currentUserObj, $currentUser);
            }

            //delte file
            $fileToDelete = new Img();
            $fileToDelete->loadImage($_POST["deleteImg"], $currentUserId);
            $fileToDelete->deleteImg();

            //if upload img is submitted
        } else {
            if (!isset($_POST["postImg"])) $_POST["postImg"] = "";

            //create img object
            $newImg = new Img();

            //insert and validate img data 
            $errImg = $newImg->uploadImg($currentUserId);
        }
    }

    require "templates/user/uploadsPage.php";
} else {
    require "templates/loginToContinue.php";
}
?>