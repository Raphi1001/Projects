<?php
if ($currentUser = $db->getUser($_SESSION["currentUser"])) {
    $placeholder = array(
        "gender" => $currentUser->getProperty("gender"),
        "fname" => $currentUser->getProperty("fname"),
        "lname" => $currentUser->getProperty("lname"),
        "email" => $currentUser->getProperty("email"),
        "uname" => $currentUser->getProperty("uname")
    );
    //if a form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["page"]) && $_GET["page"] == "account") {

        //delte User
        if (isset($_POST["deleteUser"]) && $_POST["deleteUser"] == true) {
            $userToDelete = $db->getUser($_POST["deleteUser"]);

            //if errors happen when deleting die;
            if (!$db->deleteUser($userToDelete->getProperty("id")) || !$userToDelete->deleteUserDir()) {
                Alert::echoAlert("Ein Fehler ist aufgetreten: Ihr Account wurde nicht vollständig gelöscht!", false);
                die;
            }
?>
            <script type="text/javascript">
                window.location.href = "index.php?";
            </script>
            <?php
        }

        //remove profile picture
        else if (isset($_POST["removePp"]) && $_POST["removePp"] == true) {
            $currentUser->setUserPicObj(NULL);
            $db->updateUser($currentUser, $currentUser->getProperty("uname"));
        }

        //update user data
        else if (isset($_GET["update"]) && $_GET["update"] == "user") {
            $updateUser = true;

            $oldUname = $currentUser->getProperty("uname");

            if (!isset($_POST["gender"])) $_POST["gender"] = "";
            if (!isset($_POST["fname"])) $_POST["fname"] = "";
            if (!isset($_POST["lname"])) $_POST["lname"] = "";
            if (!isset($_POST["email"])) $_POST["email"] = "";
            if (!isset($_POST["uname"])) $_POST["uname"] = "";

            $errArray = $currentUser->updateUser($_POST["gender"], $_POST["fname"], $_POST["lname"], $_POST["email"], $_POST["uname"], $currentUser->getProperty("userPicObj"));

            //check if any errors were found
            foreach ($errArray as $errMsg) {
                if ($errMsg) {
                    $updateUser = false;
                    break;
                }
            }

            //check if user already exists
            if (empty($errArray["uname"]) && $currentUser->getProperty("uname") != $_SESSION["currentUser"] && $db->getUser($currentUser->getProperty("uname"))) {
                $updateUser = false;
                $errArray["uname"] = "Dieser Username ist bereits vergeben!";
            }
            //check if email already exists
            if (empty($errArray["email"]) && $currentUser->getProperty("email") != $placeholder["email"] && $db->getEmail($currentUser->getProperty("email"))) {
                $updateUser = false;
                $errArray["email"] = "Diese E-Mail-Adresse ist bereits registriert!";
            }

            if ($updateUser) {
                //update user in database
                if ($db->updateUser($currentUser, $_SESSION["currentUser"])) {
                    $_SESSION["currentUser"] = $currentUser->getProperty("uname");
            ?>
                    <script type="text/javascript">
                        window.location.href = 'index.php?page=account';
                    </script>
    <?php
                }
                //update has failed
                else {
                    $updateUser = false;
                    Alert::echoAlert("Die Aktualisierung ist fehlgeschlagen!", false);
                }
            }

            if (!$updateUser) {
                foreach (array_keys($placeholder) as $key) {
                    if ($currentUser->getProperty($key)) $placeholder[$key] = $currentUser->getProperty($key);
                }
            }
        }
        //update password
        else if (isset($_GET["update"]) && $_GET["update"] == "password") {
            $updatePwd = true;

            if (!isset($_POST["oldPwd"])) $_POST["oldPwd"] = "";
            if (!isset($_POST["pwd"])) $_POST["pwd"] = "";
            if (!isset($_POST["pwdCheck"])) $_POST["pwdCheck"] = "";

            $errArray = array();

            //check if old password is correct
            if ($db->loginUser($_SESSION["currentUser"], $_POST["oldPwd"])) {
                $errArray = $currentUser->updatePassword($_POST["pwd"], $_POST["pwdCheck"]);
            } else {
                $errArray["oldPwd"] = "Falsches Passwort!";
            }

            //check if any errors were found
            foreach ($errArray as $errMsg) {
                if ($errMsg) {
                    $updatePwd = false;
                    break;
                }
            }

            if ($updatePwd) {
                //update user password in database
                if ($db->updatePassword($currentUser->getProperty('uname'), $currentUser->getProperty('pwd'))) {
                    Alert::echoAlert("Ihr Passswort wurde erfolgreich ge&auml;ndert!", true);
                }
                //update has failed
                else {
                    $updatePwd = false;
                    Alert::echoAlert("Ihr Passwort konnte nicht ge&auml;ndert werden!", false);
                }
            }
        }
    }
}
//if getUser fails
else {
    $placeholder = array(
        "gender" => "",
        "fname" => "",
        "lname" => "",
        "email" => "",
        "uname" => ""
    );
    Alert::echoAlert("Ein Fehler ist aufgetreten! Kein Zugriff auf User-Daten!", false);
}

//show user update form
if (isset($_GET["update"]) && $_GET["update"] == "user") {
    require "templates/user/accUpdateForm.php";
}
//show password change form
else if (isset($_GET["update"]) && $_GET["update"] == "password") {
    require "templates/user/newPwdForm.php";
} //Show user posts
else if (isset($_GET["update"]) && $_GET["update"] == "posts") {
    require "src/post/sort.php";
    require "src/post/likes.php";
    ?>
    <div class="row fixed-top backButton foreground">
        <a href="index.php?page=<?= $_GET["page"] ?>" class="btn btn-dark" role="button">Zur&uuml;ck</a>
    </div>
<?php
    $postArray = $db->getUserPosts($currentUser->getProperty("id"), $sortBy, false);
    Post::printPostArray($postArray, $db, $sortBy);
}
//show account info
else {
    require "templates/user/accInfoPage.php";
}
