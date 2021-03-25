<?php
    //default value for loggedin
    if (!isset($_SESSION["loggedin"])) {
        $_SESSION["loggedin"] = false;
    } 
    
    //default value for currentUser
    if (!isset($_SESSION["currentUser"])) {
        $_SESSION["currentUser"] = "";
    }

    if (!empty($_SESSION['currentUser'])) {
        $currUser = $db->getUser($_SESSION['currentUser']);
         //logout user if user is deleted or is inactive
        if ($currUser == null || $currUser->getProperty('isActive') == false) {
            $_SESSION = array();
            $_SESSION["loggedin"] = false;
            Alert::echoAlert("Ihre Account ist nicht länger verf&uuml;gbar!", false);
        }
    }

    //logout user if user clicks on logout link 
    if ((isset($_GET["logout"]) && $_GET["logout"]=="true")) {
        $_SESSION = array();
        $_SESSION["loggedin"] = false;
        Alert::echoAlert("Logout erfolgreich!", true);
    } 
    //Display Allert for newly registered users
    if ((isset($_GET["newRegister"]) && $_GET["newRegister"]=="true")) {
        Alert::echoAlert("Der Account wurden erfolgreich erstellt!", true);
    } 

    //check login form
    if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_GET['page']) && $_GET['page'] == 'login') {
        if(!empty($_POST["inputUname"])) {
            $username = $_POST["inputUname"]; 
        } 
        else {
            $username = "";
        }
        
        if(!empty($_POST["inputPw"])) {
            $password = $_POST["inputPw"];
        } 
        else {
            $password = "";
        }

        if ($db->loginUser($username, $password)) {
            $user = $db->getUser($username);
            if ($user->getProperty("isActive")) {
                $_SESSION["loggedin"] = true;
                $_SESSION["currentUser"] = $username;
                $_SESSION["currentUserId"] = $user->getProperty("id");
                $user->createUserDir($db); //create user directory if it doesn't exist
                Alert::echoAlert("Login erfolgreich!", true);
            } else {
                Alert::echoAlert("Dieser Account wurde gesperrt! Kontaktieren Sie einen Admin für mehr Informationen.", false);
            }
        } 
        else {
            Alert::echoAlert("Falscher Benutzername oder Passwort!", false);
        }
    }
