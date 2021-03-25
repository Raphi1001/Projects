<?php
$placeholder = array(
    "gender" => "",
    "fname" => "",
    "lname" => "",
    "email" => "",
    "uname" => ""
);
//if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["page"]) && $_GET["page"] == "register") {
    $createUser = true;
    if (!isset($_POST["gender"])) $_POST["gender"] = "";
    if (!isset($_POST["fname"])) $_POST["fname"] = "";
    if (!isset($_POST["lname"])) $_POST["lname"] = "";
    if (!isset($_POST["email"])) $_POST["email"] = "";
    if (!isset($_POST["uname"])) $_POST["uname"] = "";
    if (!isset($_POST["pwd"])) $_POST["pwd"] = "";
    if (!isset($_POST["pwdCheck"])) $_POST["pwdCheck"] = "";

    //create User object
    $newUser = new User();

    //insert and validate data from $_POST
    $errArray = $newUser->registerUser($_POST["gender"], $_POST["fname"], $_POST["lname"], $_POST["email"], $_POST["uname"], $_POST["pwd"], $_POST["pwdCheck"]);

    //fill placeholder array
    foreach (array_keys($placeholder) as $key) {
        if ($newUser->getProperty($key)) $placeholder[$key] = $newUser->getProperty($key);
    }

    //check if any errors were found
    foreach ($errArray as $errMsg) {
        if ($errMsg) {
            $createUser = false;
            break;
        }
    }

    //check if user already exists
    if (empty($errArray["uname"]) && $db->getUser($newUser->getProperty('uname'))) {
        $createUser = false;
        $errArray["uname"] = "Dieser Username ist bereits vergeben!";
        $placeholder["uname"] = "";
    }
    //check if email already exists
    if (empty($errArray["email"]) && $db->getEmail($newUser->getProperty('email'))) {
        $createUser = false;
        $errArray["email"] = "Diese E-Mail-Adresse ist bereits registriert!";
        $placeholder["email"] = "";
    }

    if ($createUser) {
        //insert user into database
        if ($db->insertUser($newUser)) {

            //create directory for new user
            $newUser = $db->getUser($newUser->getProperty("uname"));
            $newUser->createUserDir($db);
    
            //clear placeholder array
            foreach (array_keys($placeholder) as $key) {
                $placeholder[$key] = "";
            }

            //call login or administration page depending on current User status
            if (isset($_SESSION["currentUser"]) && $db->getAdminStatus($_SESSION["currentUser"]) == true) {
                $pageToLoad = "administration";
            } else {
                $pageToLoad = "login";
            }
?>
            <script type="text/javascript">
                window.location.href = 'index.php?page=<?= $pageToLoad ?>&newRegister=true';
            </script>
<?php

        }
        //insertion has failed
        else {
            $createUser = false;
            Alert::echoAlert("Die Registrierung ist fehlgeschlagen!", false);
        }
    }
}

//call registerform
require "templates/user/registerForm.php";
?>