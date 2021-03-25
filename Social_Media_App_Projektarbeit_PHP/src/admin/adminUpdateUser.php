<?php
//check if user is admin
if ($_SESSION["loggedin"] && $db->getAdminStatus($_SESSION["currentUser"])) {

    if (isset($_GET["userToUpdate"])) {
        $userToUpdate = $db->getUser($_GET["userToUpdate"]);
        $oldUname = $userToUpdate->getProperty("uname");
        $_SESSION["userToUpdate"] =  $userToUpdate->getProperty("uname");

        //remove user profil picture
        if (isset($_GET["removePp"]) && $_GET["removePp"] == true) {
            $userToUpdate->setUserPicObj(NULL);
            $db->updateUser($userToUpdate, $userToUpdate->getProperty("uname"));
        }
        $updateUser = true;
    } else if (isset($_SESSION["userToUpdate"])) {
        $userToUpdate = $db->getUser($_SESSION["userToUpdate"]);
        $oldUname = $userToUpdate->getProperty("uname");

        $updateUser = true;
    //if no selected user is found 
    } else {
        $updateUser = false;
        Alert::echoAlert("Fehler: Etwas ist fehlgeschlagen: Versuchen Sie es nochmal!", false);
    }
    //set placeholders for template
    if ($updateUser) {
        $placeholder = array(
            "gender" => $userToUpdate->getProperty("gender"),
            "fname" => $userToUpdate->getProperty("fname"),
            "lname" => $userToUpdate->getProperty("lname"),
            "email" => $userToUpdate->getProperty("email"),
            "uname" => $userToUpdate->getProperty("uname")
        );
    } else {
        $placeholder = array(
            "gender" => "",
            "fname" => "",
            "lname" => "",
            "email" => "",
            "uname" => ""
        );
    }

    if (isset($_GET["page"]) && $_GET["page"] == "updateUser") {
        //delete User
        if (isset($_GET["delete"]) && $_GET["delete"] == "true") {
            $delErr = "false";
            //if user to delet is admin
            if ($userToUpdate->getProperty("isAdmin") == true) {
                $delErr = "admin";
            } else {
                //if errors happen when deleting
                if (!$db->deleteUser($userToUpdate->getProperty("id")) || !$userToUpdate->deleteUserDir()) {
                    $delErr = "true";
                }
            }
            //redirect to admin page with alert
?>
            <script type="text/javascript">
                window.location.href = "index.php?page=administration&delErr=<?= $delErr ?>";
            </script>
            <?php

        }
        //if a form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //update user data
            if (isset($_GET["update"]) && $_GET["update"] == "user") {
                $updateUser = true;

                if (!isset($_POST["gender"])) $_POST["gender"] = "";
                if (!isset($_POST["fname"])) $_POST["fname"] = "";
                if (!isset($_POST["lname"])) $_POST["lname"] = "";
                if (!isset($_POST["email"])) $_POST["email"] = "";
                if (!isset($_POST["uname"])) $_POST["uname"] = "";
                if (!isset($_POST["isActive"])) $_POST["isActive"] = "";
                $errArray = $userToUpdate->updateUser($_POST["gender"], $_POST["fname"], $_POST["lname"], $_POST["email"], $_POST["uname"], $userToUpdate->getProperty("userPicObj"));

                //Update isActive Status
                if ($_POST["isActive"] == true && $userToUpdate->getProperty("isAdmin") == true) {
                    $errArray["isActive"] = "Sie können keinen Admin Account sperren!";
                } else {
                    $userToUpdate->updateIsActive($_POST["isActive"]);
                }
                //check if any errors were found
                foreach ($errArray as $errMsg) {
                    if ($errMsg) {
                        $updateUser = false;
                        break;
                    }
                }

                //check if user already exists
                if (empty($errArray["uname"]) && $userToUpdate->getProperty("uname") != $oldUname && $db->getUser($userToUpdate->getProperty("uname"))) {
                    $updateUser = false;
                    $errArray["uname"] = "Dieser Username ist bereits vergeben!";
                }
                //check if email already exists
                if (empty($errArray["email"]) && $userToUpdate->getProperty("email") != $placeholder["email"] && $db->getEmail($userToUpdate->getProperty("email"))) {
                    $updateUser = false;
                    $errArray["email"] = "Diese E-Mail-Adresse ist bereits registriert!";
                }

                if ($updateUser) {
                    //update user in database
                    if ($db->updateUser($userToUpdate, $oldUname)) {
                        $db->changeActiveStatus($userToUpdate->getProperty("isActive"), $userToUpdate->getProperty("id"));

            ?>
                        <script type="text/javascript">
                            window.location.href = 'index.php?page=administration';
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
                        if ($userToUpdate->getProperty($key)) $placeholder[$key] = $userToUpdate->getProperty($key);
                    }
                }
            }
            //update password and send email to user
            else if (isset($_GET["update"]) && $_GET["update"] == "password") {
                if (strlen($_POST["newPwd"]) >= 8) {
                    if ($db->updatePassword($userToUpdate->getProperty("uname"), $_POST["newPwd"])) {
                        $userEmail = $userToUpdate->getProperty("email");
                        $subject = 'Passwort Reset "Crew.at"';
                        $message = "<p>Sehr geehrter User!<br><br>Ein Admin hat Ihr Passwort zur&uuml;ckgesetzt.<br>Sie k&ouml;nnen sich nun mit Ihrem neuen Passwort wie gewohnt auf unserer Website einloggen.<br><br>Ihr neues Passwort lautet: <strong>" . $_POST["newPwd"] . "</strong><br><br>Wir freuen uns, bald wieder von Ihnen zu h&ouml;ren!<br>Falls ein Problem auftreten sollte, stehen wir Ihnen gerne jederzeit zur Verf&uuml;gung.<br><br>Ihr Crew-Team</p>";
                        $headers = "From: Crew <crew3068@gmail.com>\r\n";
                        $headers .= "Reply-To: crew3068@gmail.com\r\n";
                        $headers .= "Content-type: text/html\r\n";

                        mail($userEmail, $subject, $message, $headers);

                        Alert::echoAlert("Das Passwort wurde erfolgreich ge&auml;ndert und dem User per Email zugesendet!", true);
                    }
                }
            }
        }
    }
}
//if User is no admin
else {
    $placeholder = array(
        "gender" => "",
        "fname" => "",
        "lname" => "",
        "email" => "",
        "uname" => ""
    );
    Alert::echoAlert("Ein Fehler ist aufgetreten: Sie haben nicht die Rechte für den Zugriff auf User-Daten!", false);
}

//show user update form
if (isset($_GET["update"]) && $_GET["update"] == "user") {
    require "templates/user/accUpdateForm.php";
}
//show password change form
else if (isset($_GET["update"]) && $_GET["update"] == "password") {
    require "templates/admin/newPwdAdminForm.php";
}
//Show user posts
else if (isset($_GET["update"]) && $_GET["update"] == "posts") {
    require "src/post/sort.php";
    require "src/post/likes.php";
    ?>
    <div class="row fixed-top backButton foreground">
        <a href="index.php?page=<?= $_GET["page"] ?>" class="btn btn-dark" role="button">Zur&uuml;ck</a>
    </div>
<?php
    $postArray = $db->getUserPosts($userToUpdate->getProperty("id"), $sortBy, false);
    Post::printPostArray($postArray, $db, $sortBy);
}
//show account info
else {
    require "templates/admin/adminUserInfoPage.php";
}
